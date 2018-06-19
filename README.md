# Kinetad Online Encrpyted Journal
## About
This is an expansion of a personal project to create an encrypted online journal. Allowing me to access it from anywhere and it remain secure.
* Kinetad.com is the code in action, to use as a trial.
* The journal uses AES-256-GCM encryption with the cipher being the users password run through a SHA-512 hash.
* The journal features "autolocking" after inactivity for a little extra security
## Encryption and Security Details
The aim of the project as it currently stands, is to allow enough encrpytion that only the user can access the contents of the journal.
* User verification employs simple yet secure password salting and hashing. The users password goes through SHA-512 at the clients end using JS adding extra privacy for the user. The database only stores the salt and hash for verification - thermodynamically impossible to reverse and obtain the password from via brute force methods.
* Journal entries are stored in three parts:
  1. Encrypted text
  1. Initialization vector
  1. Message authentication code (MAC), aka TAG
These three strings are combined with the users password run through SHA-512 to decrypt the journal messages.
The TAG acts as a checksum and will inform the decryption process if the message has been altered and not output anything.
## Features
Online journals have great features over normal ones, kinetad builds on these:
* Not having to worry about carrying around a physical journal and pen.
* Not having to worry about loosing a physical journal.
* Consistent clarity on written text over handwritten notes.
* Automated date and time logging.
* Full security.
* `CRTL + f`
* Opportunity to export and analyse your entries (great to see how often you talk about certain things or more).
 ## Usage
[Kinetad Online Journal](https://www.kinetad.com "Kinetad - open source online journal") is a great place to try it out. Simply visit [The regristration page](https://www.kinetad.com/register "Register for kinetad") input a unique username and strong password **THIS PASSWORD CANNOT BE CHANGED** and off you go.
If you're a little more into running your own instance you'll need a hosting running php and a SQL database. MORE DETAIL HERE
### Password 
Your password forms part of your cipher and so cannot be changed. This would require re-encrypting your whole journal. A work-around is to create a new account with an updated password, export you entries from your old journal, and reimport them into your new account.
