<?php

namespace App\Http\Controllers;
use App\Documents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use PDF;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Facades\Storage;
class DocumentsController extends Controller
{
    public function index()
    {
        $cases = Documents::all();
        
        return response()->json([
            'status'     => 'success',
            'cases'     => $cases,
        ]);
    }

    public function getall($id) {
        $documents = Documents::where('case_id', $id)->get();

        return response()->json([
            'status'     => 'succes',
            'documents'     => $documents,
        ]);
    }

    public function getTemplates() {

        $client = new \HelloSign\Client(env('MIX_HELLOSIGN_API_KEY'));
        $template_list = $client->getTemplates();

        $availableTemplates = array();

        foreach ($template_list as $template) {
            $template_elements = array();
                $template_elements['title'] = $template->title;
                $template_elements['signer_roles'] = $template->signer_roles;
                $template_elements['templateId'] = $template->template_id;
                
            
            array_push($availableTemplates, $template_elements);
        }

        return response()->json([
            'status'     => 'succes',
            'template'     => $availableTemplates,
        ]);
    }

    public function editSingle(Request $request, $id) {
        $document = Documents::find($id);
        $document->content = $request->content;

        $document->save();

        return response()->json([
            'status'     => 'success',
            'document'     => $document,
        ]);
    }

    public function create(Request $request) {

        $document = new Documents;
        $document->content = $request->content;
        $document->case_id = $request->caseId;
        $document->template_id = $request->templateId;
        $document->status = 'draft';


        $document->save();

        return response()->json([
            'status'     => 'success',
            'document'     => $document,
        ]);
    }

    public function delete($id)
    {
        $document = Documents::find($id);
        if(strcasecmp($document->status, 'draft') == 0) { 
            $document->delete();
        } else {
            return $document;
        }
    }

    public function attach(Request $request, $id)
    {   
        $document = Documents::find($id);

        list('mediator' => $mediator,
             'opener' => $opener, 
             'recipient' => $recipient,
             'templateId' => $templateId) 
             = $request;
        
        if(strcasecmp($document->status, 'attached') == 0) { 
            return 'doc attached already';
        } else {
            
            $client = new \HelloSign\Client(env('MIX_HELLOSIGN_API_KEY'));
            $client_id = env('MIX_HELLOSIGN_CLIENT_ID');

            $request = new \HelloSign\TemplateSignatureRequest;
            $request->enableTestMode();

            $request->setTemplateId($templateId);

            $request->setSigner('Mediador',$mediator['email'], $mediator['name'].' '.$mediator['lastname']);
            $request->setSigner('Parte A', $opener['email'], $opener['name'].' '.$opener['lastname']);
            $request->setSigner('Parte B', $recipient['email'], $recipient['name'].' '.$recipient['lastname']);

            $embedded_request = new \HelloSign\EmbeddedSignatureRequest($request, $client_id);
            $response = $client->createEmbeddedSignatureRequest($embedded_request);

            $signatures = $response->getSignatures();
            $signature_request_id = $response->getId();

            foreach ($signatures as $key=>$signature) {
                if(!$key) $document->mediator_sign_id = $signature->getId();
                if($key == 1) $document->opener_sign_id = $signature->getId();
                if($key == 2) $document->recipient_sign_id = $signature->getId();
            }
            
            // $signUrl = $client->getEmbeddedSignUrl($mediatorSign);
            // $sign_url = $signUrl->getSignUrl();
        }

        $document->signatureId = $signature_request_id;
        $document->status = 'attached'; 
        $document->save();

        return response()->json([
            'status' => 'success',
            '$signatures' => $signature_request_id,
            // 'signUrl' => $sign_url,
            'documentRecord' => $document,
            // 'clientId' => $client_id,
        ]);
    }

    public function downloadPdf($id)
    {
        $document = Documents::find($id);

        $client = new \HelloSign\Client(env('MIX_HELLOSIGN_API_KEY'));
        $doc_object = $client->getFiles($document->signatureId, null, \HelloSign\SignatureRequest::FILE_TYPE_ZIP);
        $doc = $doc_object->getFileUrl();

        return response()->json([
            'status' => 'success',
            '$document' => $document,
            '$doc' => $doc
        ]);

        // $rawPdfData = $this->getPdf($id);

        // return response($rawPdfData[0], 200)
        //     ->header('ContentType', $rawPdfData[1]['mimetype'])
        //     ->header('Content-Disposition', "attachment; filename=" . $id . '-acta.pdf');
    }

    public function helloSign(Request $request)
    {
        $client = new \HelloSign\Client(env('MIX_HELLOSIGN_API_KEY'));
        $client_id = env('MIX_HELLOSIGN_CLIENT_ID');

        // Retrieve the URL to sign the document
        $response = $client->getEmbeddedSignUrl($request->signatureId);
        $sign_url = $response->getSignUrl();

        return response()->json([
            'clientId' => $client_id,
            'status' => 'success',
            'signUrl' => $sign_url
        ]);
    }

    public function saveSign(Request $request, $id) {
        $document = Documents::find($id);

        $document[$request->signerType] = $request->signatureId;

        $document->save();

        return response()->json([
            'status' => 'success',
            '$document' => $document
        ]);
    }

    private function getPdf($id) {
        $filename = $id . '-acta.pdf';

        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        //return $file; // array with file info

        $rawData = Storage::cloud()->get($file['path']);

        return [$rawData,$file];
    } 

}



