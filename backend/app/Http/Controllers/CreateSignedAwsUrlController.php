<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\Sdk;

class CreateSignedAwsUrlController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $sdk = new Sdk([
            'region' => config('filesystems.disks.s3.region'),
            'version' => 'latest'
        ]);

        $client = $sdk->createS3();

        $expiry = "+30 minutes";

        $cmd = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $request->name,
            'ACL' => 'public-read',
        ]);

        $request = $client->createPresignedRequest($cmd, $expiry);
        $presignedUrl = (string) $request->getUri();

        return response()->json(['url' => $presignedUrl], 201);
    }
}
