# PHPChain

## Summary

This system will securely store passwords in a database, using (by 
default) blowfish encryption. The objective is to provide a site which 
users can log into from anywhere and retrieve their passwords, should they 
forget them. 

This was originally written long before password databases were commonplace.
These days you have many options, such as LastPass, Keepass, 1Password, etc.
Indeed, most browsers now have good, built-in, sychronized databases, so the
need for this system is significantly lower than it used to be.

However, if you prefer to host your own system and not be beholden to someone
else for the long term availability of your data, this may be an option to
consider.

## Requirements

The system requires PHP7 with MySQL support, and libmcrypt support. 

mcrypt has been deprecated from PHP 7, but is still available as a PECL Module
See this page for more information:

https://pecl.php.net/package/mcrypt
https://lukasmestan.com/install-mcrypt-extension-in-php7-2/

Those pages existed at the time of publication, but if they no longer do, you
are on your own.

It's STRONGLY recommended that you run this system on a web server with SSL 
support. If the site is not secured using SSL, the whole point of having a 
secure database is lost. Configuring SSL is beyond the scope of this 
document. See the following site for information:

http://www.modssl.org/

*IF YOU DO NOT USE SSL. YOUR PASSWORDS WILL BE SENT PLAINTEXT OVER THE INTERNET!*

There's no excuse for not using SSL any more: https://letsencrypt.org/

The system was originally built on and tested with apache 1.3.27, PHP 4.2.2, 
mod_ssl 2.8.14 with OpenSSL 0.9.7b, all running on Linux. These days, it has
been tested on Apache 2.2.49, PHP 7.2.

Other platforms may also work, but have not been tested.


## Licensing

PHPChain is available under the terms of the GNU General Public License (GPL).
A copy of the GPL is included in the archive.


## Security

This system will store a site name, URL (if applicable), login and 
password. All this data is encrypted using the Blowfish algorithm in CBC 
mode. If you wish to change this, see the functions in the file 
inc/crypt.php and the PHP mcrypt page referenced above for further 
information.

Each user's password to actually log into the system is not stored in the 
database. Instead, when their login is created, a random 12 character 
string is generated and then added to itself, producing a 24 character 
string. This value is then encrypted using the key provided by the user 
and stored in the database.
 At login time, this value is retrieved from the database and decrypted. 
If the first 12 characters match the last 12 characters, the login is 
considered to be valid. Since the decryption key is never stored in the 
database, this adds an additional level of security should the database 
ever be compromised. The chances of a random key decoding the stored value 
such that the first 12 characters matched the last 12 are absolutely 
miniscule. In the incredibly unlikely event that this happened, the data 
would still be secure, since the key is invalid for the actual decoding.

The key itself is stored as an md5 hash as a cookie in the client's 
browser for the duration of the session. This may present a security risk 
in browsers that  do not remove invalid cookies when the browser is 
closed. To be safe, users should click the logout button when they're 
done, which not only explicitely removes the cookies, but overwrites the 
key cookie with garbage data before removing it.

Only 3 failed login attempts are allowed per 10 minutes. Failed login 
attempts are also logged and reported to the user on their subsequent 
login.


## Contact Information

The website for this tool is at http://www.globalmegacorp.org/PHPChain
The code is also available via github at https://github.com/Globalmegacorp/PHPChain
The author's email is james@globalmegacorp.org

If you think you've discovered a security problem with this system, please 
contact me immediately, or even better, submit a pull request.
