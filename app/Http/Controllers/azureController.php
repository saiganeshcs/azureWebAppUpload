<?php

namespace App\Http\Controllers;

// require_once "vendor/autoload.php";
// require_once 'WindowsAzure\WindowsAzure.php';

// use WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\ListContainersOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class azureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('azure.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'file' => 'required ',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        // dd($request->all());
        $fileToUpload = $request->file('file');
        if ($request->input('blob_name') != 'null') {
            $containerName = $request->input('blob_name');
        } elseif ($request->input('newContainerInput') != 'null') {
            $containerName = $request->input('newContainerInput');
        }
        // $containerName = $request->input('blob_name');


        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=saft;AccountKey=/ikoRaUtk8CrRXcBYOrwJ3z8Oor5hyJfQlqfp2TZhIdtvuHiMVhy4uPGT8a2q4A1nQpCsfTHz2of/j39pZ8tpg==;EndpointSuffix=core.windows.net';
        $blobClient = BlobRestProxy::createBlobService($connectionString);
        $createContainerOptions = new CreateContainerOptions();
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
        // Set container metadata.
        $createContainerOptions->addMetaData("key1", "value1");
        $createContainerOptions->addMetaData("key2", "value2");
        // dd($containerName);
        $getContainer = $this->createContainerIfNotExists($blobClient, $containerName);
        try {
            $content = fopen($fileToUpload, "r");
            //Upload blob
            $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return view('azure.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    function createContainerIfNotExists($blobRestProxy, $upload)
    {
        // See if the container already exists.
        $listContainersOptions = new ListContainersOptions();
        $listContainersOptions->setPrefix($upload);
        // $list = $blobRestProxy->listContainers();
        $listContainersResult = $blobRestProxy->listContainers($listContainersOptions);
        //  dd($list);
        $containerExists = false;
        foreach ($listContainersResult->getContainers() as $container) {
            if ($container->getName() == $upload) {
                // The container exists.
                $containerExists = true;
                // No need to keep checking.
                return $containerExists;
            }
        }
        if (!$containerExists) {
            echo "Creating container.\n";
            $blobRestProxy->createContainer($upload);
            return "Container '" . $upload . "' successfully created.\n";
        }
    }
}
