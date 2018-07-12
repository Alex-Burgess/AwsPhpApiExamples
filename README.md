# PhpAwsApiExamples
## Introduction
A series of short examples of how to use the PHP AWS API to interact with AWS services. An emphasis is placed on using encryption (via KMS) with the services.  Service examples so far include:
* S3
* (EBS to be added)
* (Aurora to be added)
* (Etc)

## Application Stack Overview
The following CloudFormation template can be used to create an application stack:  [aws_php_api_examples.template](https://github.com/Alex-Burgess/AwsPhpApiExamples/blob/master/cloudformation-templates/aws_php_api_examples.template).  This creates:
* A PHP application deployed on Elastic Beanstalk
* Two S3 buckets (one encrypted, one not), which store image files
* A role which the Elastic Beanstalk service role can assume to obtain permissions to interact with the S3 buckets created
* An encryption key in KMS, which encrypts the S3 bucket and is used by the application to perform actions with the bucket.

To save money the Elastic Beanstalk application is deployed using spot instances. This is configured in the .ebextensions.. (link) with the spot price provided as a parameter in the CloudFormation template.  **Note** that at the time of development Elastic Beanstalk did not offer a full feature set with respect to using spot instances. In the rare event that the spot price exceeds the price configured, the instance will be terminated and **NOT** replaced by an ondemand instance, rendering the application unavailable.  If this proves to be a problem the stack can be updated with a higher spot price, or the spot configuration file can be removed and the application re-deployed.

## Deployment
The below procedure deploys the application from scratch:
1. Create zip file (from the terminal):
      ```
      $ cd <loc>/AwsPhpApiExamples
      $ zip -r -X php-api-code-examples_vX.Y.Z.zip * .ebextensions/ -x "vendor/*" "cloudformation-templates/*" "test/*"
      ```
1. Upload zip file to repo bucket (replace bucket with own bucket):
      ```
      $ aws s3 cp php-api-code-examples_vX.Y.Z.zip s3://alex-demo-files/php-applications/
      ```
1. Upload CloudCormation template to repo bucket (replace bucket with own bucket):
      ```
      $ aws s3 cp cloudformation-templates/aws_php_api_examples.template s3://alex-demo-files/cf-templates/
      ```
1. Create CloudFormation stack:
      ```
      $ aws cloudformation create-stack \
       --stack-name "AWS-PHP-Examples-Green" \
       --template-url https://s3-eu-west-1.amazonaws.com/alex-demo-files/cf-templates/aws_php_api_examples.template \
       --capabilities CAPABILITY_NAMED_IAM \
       --parameters ParameterKey=DeploymentName,ParameterValue=green ParameterKey=ApplicationBucketLocation,ParameterValue=alex-demo-files ParameterKey=ApplicationKeyLocation,ParameterValue="php-applications/php-api-code-examples_vX.Y.Z.zip" \
       --tags Key=Application,Value=AWS-PHP-Examples-Green
      ```
1. Loading image files to new S3 Buckets (replace bucket with own bucket)
      ```
      aws s3 cp s3://alex-demo-files/images/ s3://php-aws-examples/ --recursive
      aws s3 cp s3://alex-demo-files/images/ s3://php-aws-examples-sse/ --recursive
      ```
1. Check the progress of the stack update:
      ```
      $ aws cloudformation describe-stack-events --stack-name "AWS-PHP-Examples-Green"
      ```
1. Accessing the application:
**Add an output for the application url**

## Updating the Application Bundle
1. Create zip file and upload to repo bucket:
      ```
      $ cd <loc>/AwsPhpApiExamples
      $ zip -r -X php-api-code-examples_vX.Y.Z.zip * .ebextensions/ -x "vendor/*" "cloudformation-templates/*" "test/*"
      $ aws s3 cp php-api-code-examples_vX.Y.Z.zip s3://alex-demo-files/php-applications/
      ```
1. Create change set update (Provide a change-set-name and the location to the new php bundle):
      ```
      $ aws cloudformation create-change-set \
       --change-set-name "DescribeTemplateUpdate" \
       --stack-name "AWS-PHP-Examples-Green" \
       --capabilities CAPABILITY_NAMED_IAM \
       --parameters ParameterKey=DeploymentName,ParameterValue=green ParameterKey=ApplicationBucketLocation,ParameterValue=alex-demo-files ParameterKey=ApplicationKeyLocation,ParameterValue="php-applications/php-api-code-examples_vX.Y.Z.zip"  \
       --template-url https://s3-eu-west-1.amazonaws.com/alex-demo-files/cf-templates/aws_php_api_examples.template
      ```
1. Execute the change set (After checking that the change set is as expected, execute the change set):
      ```
      $ aws cloudformation execute-change-set \
        --change-set-name "arn:aws:cloudformation:eu-west-1:369331073513:changeSet/DescribeTemplateUpdate/12d23ce3-5a63-4686-a39f-45376af984a6"
      ```

Alternatively, if you are happy it is just a bundle update, you can do just a direct update.  (This is not best practice but ok for testing):
```
aws cloudformation update-stack \
   --stack-name "AWS-PHP-Examples-Green" \
   --capabilities CAPABILITY_NAMED_IAM \
   --parameters ParameterKey=DeploymentName,ParameterValue=green ParameterKey=ApplicationBucketLocation,ParameterValue=alex-demo-files ParameterKey=ApplicationKeyLocation,ParameterValue="php-applications/php-api-code-examples_vX.Y.Z.zip"  \
   --template-url https://s3-eu-west-1.amazonaws.com/alex-demo-files/cf-templates/aws_php_api_examples.template
```

## Updating the Application Stack
1. Update and upload the CF template:
      ```
      aws s3 cp cloudformation-templates/aws_php_api_examples.template s3://alex-demo-files/cf-templates/
      ```
1. Create a change set:
      ```
      $ aws cloudformation create-change-set \
       --change-set-name "DescribeTemplateUpdate" \
       --stack-name "AWS-PHP-Examples-Green" \
       --capabilities CAPABILITY_NAMED_IAM \
       --parameters ParameterKey=DeploymentName,ParameterValue=green ParameterKey=ApplicationBucketLocation,ParameterValue=alex-demo-files ParameterKey=ApplicationKeyLocation,ParameterValue="php-applications/php-api-code-examples_v1.0.X.zip"  \
       --template-url https://s3-eu-west-1.amazonaws.com/alex-demo-files/cf-templates/aws_php_api_examples.template
      ```
1. Execute the change set (Update change set name):
      ```
      $ aws cloudformation execute-change-set \
        --change-set-name "arn:aws:cloudformation:eu-west-1:369331073513:changeSet/DescribeTemplateUpdate/12d23ce3-5a63-4686-a39f-45376af984a6"
      ```

## Local Execution of Code (MacOS)
The following can be used to run a php server locally:
```
$ php -S localhost:8000
```

### Notes and Assumptions
1. The simplest way to get going with local testing is to create a stack with appropriate parameters to create the necessary AWS components.  
1. The application will authenticate to S3 using the local credentials (Access Key and Secret) so this must be configured.
1. The application will connect to the bucket configured in the application (config/app.ini).
1. The testing user will need to have permission to perform actions with the KMS certificate encrypting the S3 bucket.  An IAM user/role can be added as a parameter to the cloudformation stack.

## Deleting the stack
1. Empty S3 buckets
      ```
      $ aws s3 rm s3://php-aws-examples --recursive
      $ aws s3 rm s3://php-aws-examples-sse --recursive
      ```
1. Delete the stack:
      ```
      $ aws cloudformation delete-stack --stack-name "AWS-PHP-Examples-Green"
      ```
