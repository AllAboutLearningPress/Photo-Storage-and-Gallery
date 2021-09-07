# Gallery for storing images

## Overview 
This app is created with Laravel as backend framework and Vue.js as frontend framework. An additional library called 
Inertia.js is used, in place of vue router. So a user can navigate through the application while getting a SPA 
experience. Due to using inertia.js we can use Laravel routes. 

Files are stored in aws s3/s3 compatible storage. There is a lambda function that is run after every successfull upload.
This function creates different sizes of the image for preview and for thumbnail. Sharap (image manupulation library in node.js) was used 
to do this task. 

### Supported formats
This app currently supports jpeg, jpg, png, psd and more support is coming soon . 


## Invitation
Invitation link in email will be valid for 7 days. If the send-again feature is used. Then new 
link will be valid for 7 days again.
