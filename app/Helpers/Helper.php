<?php

use MicrosoftAzure\Storage\Blob\BlobRestProxy;

function getListOfContainers()
{
    $connectionString = 'DefaultEndpointsProtocol=https;AccountName=saft;AccountKey=/ikoRaUtk8CrRXcBYOrwJ3z8Oor5hyJfQlqfp2TZhIdtvuHiMVhy4uPGT8a2q4A1nQpCsfTHz2of/j39pZ8tpg==;EndpointSuffix=core.windows.net';
    $blobRestProxy = BlobRestProxy::createBlobService($connectionString);
    $list = $blobRestProxy->listContainers();
    return $list;
}
