# Kinetad Online Encrypted Journal
## About
This is an expansion of a personal project to create an encrypted online journal. Allowing me to access it from anywhere and it remain secure.
* Kinetad.com/register is the code in action, to use as a trial.
* The journal uses double AES-256-GCM encryption - 
	* Client side - with a passphrase that exists client-side that never passes to the server.
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

The raw text sent to the server for encryption may also be run through a client-side JS implementation of AES-256-GCM. This is a further privacy option for the user as the host could otherwise save the parsed password and use it to decrypt the sever side encrypted data. With client-side encryption, the passphrase never leaves the client's browser and hence data cannot be decrypted by anybody other than that who created it.

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
[Kinetad Online Journal](https://www.kinetad.com "Kinetad - open source online journal") is a great place to try it out. Below are the different aspects to explore:
### Register
Visit the [Registration page](https://www.kinetad.com/register "Kinetad - open source online journal"), input a unique username, and a strong password and off you go.
### Adding Entries
Entries are written paragraph by paragraph with the option of a client side encryption to be performed with a passphrase. Each entry can use a different passphrase (though not advised).
### Adding Images
A new addition is to add image entries. This is done by first previewing images that are compressed client side. Uploading the image encrpytes in client and server side in a similar method to text entries. Images are stored as Medium Blobs in the database.
### passphrase
Your passphrase (if used) is solely client side and so **at present cannot be changed** as there is no simple way to mix client and server side encryption rewriting without compromising the security of the users data. When making a new entry with a passphrase, the passphrase is written as a cookie to your browser to make adding multiple entries easier. The cookie expires when you logout.
### Password 
Your password forms part of your server-side encryption cipher and so cannot be easily be changed. To do so, your entire journal needs to be re-encrypted. This is done via the [Change password page](https://kinetad.com/changepassword "Kinetad - open source online journal"). Changing your password will not affect your passphrase.
### Unlocking
Upon logging in, entries remain in an encrypted state for an additional privacy feature in case one has their browser save passwords. This is **toggled by clicking on the heading.**
### Navigation
Previous and Next day buttons do as one would expect. `CRTL + f` is a nice way to find things also. Exporting is suggested for more in-depth reviewing.
### Export
Exporting is a JS function (once again to ensure users data security isn't compromised). This function exports the entries as the appear in the browser at the time. A CSV file is generated containing:
`entry number, date, entry contents`
## Running your own instance
If you are a little more interested in running your own instance, you'll need:
* php server
* MySQL
### To install
1. Clone the whole project into the root of your domain: `git clone https://github.com/HCAWN/kinetad.git`
2. Create a new database
3. create a SQL user with the following privileges in your newly created database:
	 `CREATE`
	 `SELECT`
	 `DROP`*
	 `INSERT`
	 `UPDATE`
4. Update `includes/psl-config.php` to match the SQL login and database information.
5. Run the following SQL command to create the members table and login failure table:
```sql
--
-- Table structure for table `login_attempts`
--
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
)
ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Table structure for table `members`
--
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
```
6. Use as outlined above!
