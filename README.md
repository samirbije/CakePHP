System Requirements

1. PHP version must be higher than 5.3.5

   PHP will also need to have the following libraries installed:


   intl
   curl
   mbstring
   xml
   

2. Installed Cakephp version 3.3.1


3. MySql version


System Flow

A simple Cake PHP application with only one view.

Said view consists of a form with the following fields:

Name, Last Name, Organization, Email, Text and Reason.

Name, Last Name, Organization will be regular inputs.

Email needs validation.

Text will be a large text box (it is supposed to be like the body of an email).

Reason will be a selector with the following options:

1. Feedback.

2. Help.

3. HR.

4. Other.

If Other option is selected, a new Field will appear “Specify”. Where you will be required to specify a reason of the contact.

All fields are mandatory.

The application stores in any format the information of the contact and send said information to a contact Email.
