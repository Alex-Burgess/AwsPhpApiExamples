# PhpAwsApiExamples
## Introduction
A series of short examples of how to use the PHP AWS API to interact with AWS services. An emphasis is placed on using encryption (via KMS) with the services.  Service examples so far include:
* S3
* (EBS to be added)
* (Aurora to be added)
* (Etc)

## Local Execution of Code (MacOS)
To run a php server locally:
```
$ php -S localhost:8000
```

*Include details about aws configure...*

## Deployment (MacOS)
Create zip file (from the terminal):
```
$ cd <loc>/AwsPhpApiExamples
$ zip -r -X php-api-code-examples_vX.Y.Z.zip * -x "*vendor*" "*WorkingSamples*" "*.template"
```

Upload zip file to S3 bucket:
```
$ aws s3 cp php-api-code-examples_vX.Y.Z.zip s3://<bucket-name>/php-applications/
```

Update CloudFormation template (aws_php_api_examples.template) with zip file version:
```
"sampleApplicationVersion": {
   ...
   "SourceBundle": {
      "S3Bucket":  "<bucket-name>",
      "S3Key": "php-applications/php-api-code-examples_vX.Y.Z.zip"
    }
    ...
  }
},
```

Upload template to S3:
```
$ aws s3 cp aws_php_api_examples.template s3://<bucket-name>/cf-templates/
```

Create CloudFormation stack:
```
$ aws cloudformation create-stack \
   --stack-name "AWS-PHP-Examples-1" \
   --template-url https://s3-eu-west-1.amazonaws.com/<bucket-name>/aws_php_api_examples.template \
   --tags Key=Application,Value=AWS-PHP-Examples-1
```
