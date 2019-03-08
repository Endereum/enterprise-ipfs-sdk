# Endereum Enterprise IPFS SDK - PHP

Endereum enterprise IPFS SDK enables an enterprise to integrate our IPFS solution to its platform. To integrate this SDK, you need to follow certain steps listed below.
1. Log in to your account
2. Register your servers IP address from where you will call our API endpoints.
3. Once you register your IP address then you can create an enterprise API key. You will need this API key to integrate our SDK with your platform.
4. Download this repository. The main file is integration.sdk.php inside dist (distribution folder).
5. Follow the example to learn the integration process.


## Integration Explanation

### Getting Enterprise ID and Connect Key
The purpose of integration.sdk.php is to get user ID (as an enterprise ID) and connect key. On successful API call, you will get these two entities. You can pass these entities along with the other fields to our IPFS API server directly.
1. Enterprise ID: Represents your enterprise identity
2. Connect Key: Key used to connect with our IPFS server

### Uploading files to our IPFS Server
You need to send these below-listed fields to our IPFS API server. After successful validation. Your file will be uploaded to the IPFS platform. In case of error, your request will be rejected.
1. user_id
2. connect_key
3. file_id
4. file_title
5. file_type
6. file_extension
7. file


## Enterprise IPFS SDK Integration Example

### Integration
1. Install PHP server on your web host.
2. Extract files of this repository into your root folder.
3. RUN example.php
4. Select file, set title and hit upload button
5. Once your data is validated  and your files starts uploading to IPFS, you will see success message.
6. To view files, you can login to endereum dashboard. Or you can create download link.

### Creating download link
1. Use the hash of the file you want to download. You can get the hash from your dashboard.
2. Create download link like this: https://ipfs.enedereum.io/ipfs/----hash-of-the-file----
3. Using this link, you can simply download the file.


## Update LOG

### V 1.0: 
1. Initial Version - Genesis Build
