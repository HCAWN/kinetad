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
