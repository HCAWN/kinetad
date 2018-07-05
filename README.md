# Kinetad Online Encrypted Journal
## About
This is an expansion of a personal project to create an encrypted online journal. Allowing me to access it from anywhere and it remain secure.
* Kinetad.com/register is the code in action, to use as a trial.
* The journal uses double AES-256-GCM encryption - 
	* Client side - with a pass phrase that exists client-side that never passes to the server.
    * Server side - with the cipher being the users password run through a SHA-512 hash.
* The journal features time stamping and dating for beautiful logging.
## Encryption and Security Details
The aim of the project as it currently stands, is to allow enough encryption that only the user can access the contents of the journal.
* User verification employs simple yet secure password salting and hashing. The user's password goes through SHA-512 at the client's end using JS, adding extra privacy for the user. The database only stores the salt and hash for verification - thermodynamically impossible to reverse and obtain the password from via brute force methods.
* Journal entries are stored server side in three parts:
  1. Encrypted text
  1. Initialization vector
  1. Message authentication code (MAC), aka TAG

These three strings are combined with the user's password run through SHA-512 to decrypt the journal messages.
The TAG acts as a checksum and will inform the decryption process if the message has been altered and not output anything.

The raw text sent to the server for encryption may also be run through a client-side JS implementation of AES-256-GCM. This is a further privacy option for the user as the host could otherwise save the parsed password and use it to decrypt the sever side encrypted data. With client-side encryption, the pass phrase never leaves the client's browser and hence data cannot be decrypted by anybody other than that who created it.

## Features
Online journals have great features over normal ones, Kinetad builds on these:
* Not having to worry about carrying around a physical journal and pen.
* Not having to worry about loosing a physical journal.
* Consistent clarity on written text over handwritten notes.
* Automated date and time logging.
* Full security.
* `CRTL + f`
* Opportunity to export and analyse your entries (great to see how often you talk about certain things or more).
 ## Usage
[Kinetad Online Journal](https://www.kinetad.com "Kinetad - open source online journal") is a great place to try it out. Simply visit [The registration page](https://www.kinetad.com/register "Register for kinetad") input a unique username and strong password and off you go.
If you're a little more into running your own instance, you'll need a hosting running php and a SQL database. MORE DETAIL HERE
### Password 
Your password forms part of your server-side encryption cipher and so cannot be easily be changed. To do so, your entire journal needs to be re-encrypted.
### Pass Phrase
Your pass phrase (if used) is solely client side and so **at present cannot be changed** as there is no simple way to mix client and server side encryption rewriting without compromising the security of the users data.

