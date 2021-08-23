<?php

class s3Utill {

    const BUCKET_NAME = "onyx-core-media-dev";

    private $s3;
    private $bucketName;

    public function __construct() {

        //$key = 'AKIAI2NXZFRG2HNJ5UBA';
        //$secret = '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';

        $key = 'AKIAIBD4U37ISOHEBJVA';
        $secret = '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';

        $this->s3 = Aws\S3\S3Client::factory([
                    'region' => 'us-east-1',
                    'version' => 'latest',
                    'scheme' => 'http',
                    'credentials' => array(
                        'key' => $key,
                        'secret' => $secret
                    )
        ]);
        $this->bucketName = self::BUCKET_NAME;
    }

    public function uploadToAmazon($tempPath, $fileName) {
        set_time_limit(0);
        try {
            $body = fopen($tempPath, 'rb');
            $this->s3->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
                'Body' => $body,
                'ACL' => 'public-read'
            ]);
            fclose($body);
            
        } catch (Aws\S3\Exception\S3Exception $e) {
            file_put_contents("test.txt", "s3 error: " . $e->__toString(), FILE_APPEND);
        }
    }

    public function delete($fileName) {
        $this->s3->deleteObject(array(
            'Bucket' => $this->bucketName,
            'Key' => $fileName
        ));
    }

    public function downloadFromAmazon($fileName, $saveAs) {
        $result = $this->s3->getObject(array(
            'Bucket' => $this->bucketName,
            'Key' => $fileName
        ));
        header("Content-Type: {$result['ContentType']}");
        header('Content-Disposition: attachment; filename=' . $saveAs);
        echo $result['Body'];
    }

    public static function getPath($bucketName, $fileName) {
        return "https://s3.amazonaws.com/" . $bucketName . "/" . $fileName;
    }

}
