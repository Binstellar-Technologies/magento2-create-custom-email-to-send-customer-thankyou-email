## Magento2 Create custom email to send customer thankyou email

> Magento2 an open-source e-commerce platform written in PHP.

&nbsp;
&nbsp;

> Here in this extension we are going to learn how to create custom email templates & send it to customers on contact us enquiry.

&nbsp;
&nbsp;

> In this extension we have used Magento2 plugin method to override the send function. So, basically afterSend function is helping to send the mail to customer as an acknowledgement after they fill the contact us form saying that one of the company's staff will get back to them.

&nbsp;
&nbsp;

> In Magento2 Plugin are classes that allow editing the behaviour of any public class or method by running code either before or after or around the function call. Here, we are overriding the send function to send customers thank you email.

&nbsp;
&nbsp;


## Installation Steps

##### Step 1 : Download the Zip file from Github & Unzip it
##### Step 2 : Create a directory under app/code/Binstellar/ContactResponse
##### Step 3 : Upload the files & folders from extracted package to app/code/Binstellar/ContactResponse
##### Step 4 : Go to the Magento2 Root directory & run following commands

php bin/magento setup:upgrade

php bin/magento setup:di:compile

php bin/magento setup:static-content:deploy -f

php bin/magento cache:flush

&nbsp;
&nbsp;

<kbd>

![image2](https://user-images.githubusercontent.com/123800304/218022217-9e7b2787-e79e-4ecf-8fac-68bdb4f28cd4.png)

</kbd>

&nbsp;
&nbsp;

## Note : We have tested this option in Magento ver. 2.4.5-p1
