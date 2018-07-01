// var sha256 = function sha256(ascii) {
// 	function rightRotate(value, amount) {
// 		return (value>>>amount) | (value<<(32 - amount));
// 	};
	
// 	var mathPow = Math.pow;
// 	var maxWord = mathPow(2, 32);
// 	var lengthProperty = 'length'
// 	var i, j; // Used as a counter across the whole file
// 	var result = ''

// 	var words = [];
// 	var asciiBitLength = ascii[lengthProperty]*8;
	
// 	//* caching results is optional - remove/add slash from front of this line to toggle
// 	// Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
// 	// (we actually calculate the first 64, but extra values are just ignored)
// 	var hash = sha256.h = sha256.h || [];
// 	// Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
// 	var k = sha256.k = sha256.k || [];
// 	var primeCounter = k[lengthProperty];
// 	/*/
// 	var hash = [], k = [];
// 	var primeCounter = 0;
// 	//*/

// 	var isComposite = {};
// 	for (var candidate = 2; primeCounter < 64; candidate++) {
// 		if (!isComposite[candidate]) {
// 			for (i = 0; i < 313; i += candidate) {
// 				isComposite[i] = candidate;
// 			}
// 			hash[primeCounter] = (mathPow(candidate, .5)*maxWord)|0;
// 			k[primeCounter++] = (mathPow(candidate, 1/3)*maxWord)|0;
// 		}
// 	}
	
// 	ascii += '\x80' // Append Ƈ' bit (plus zero padding)
// 	while (ascii[lengthProperty]%64 - 56) ascii += '\x00' // More zero padding
// 	for (i = 0; i < ascii[lengthProperty]; i++) {
// 		j = ascii.charCodeAt(i);
// 		if (j>>8) return; // ASCII check: only accept characters in range 0-255
// 		words[i>>2] |= j << ((3 - i)%4)*8;
// 	}
// 	words[words[lengthProperty]] = ((asciiBitLength/maxWord)|0);
// 	words[words[lengthProperty]] = (asciiBitLength)
	
// 	// process each chunk
// 	for (j = 0; j < words[lengthProperty];) {
// 		var w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
// 		var oldHash = hash;
// 		// This is now the undefinedworking hash", often labelled as variables a...g
// 		// (we have to truncate as well, otherwise extra entries at the end accumulate
// 		hash = hash.slice(0, 8);
		
// 		for (i = 0; i < 64; i++) {
// 			var i2 = i + j;
// 			// Expand the message into 64 words
// 			// Used below if 
// 			var w15 = w[i - 15], w2 = w[i - 2];

// 			// Iterate
// 			var a = hash[0], e = hash[4];
// 			var temp1 = hash[7]
// 				+ (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
// 				+ ((e&hash[5])^((~e)&hash[6])) // ch
// 				+ k[i]
// 				// Expand the message schedule if needed
// 				+ (w[i] = (i < 16) ? w[i] : (
// 						w[i - 16]
// 						+ (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15>>>3)) // s0
// 						+ w[i - 7]
// 						+ (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2>>>10)) // s1
// 					)|0
// 				);
// 			// This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
// 			var temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) // S0
// 				+ ((a&hash[1])^(a&hash[2])^(hash[1]&hash[2])); // maj
			
// 			hash = [(temp1 + temp2)|0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
// 			hash[4] = (hash[4] + temp1)|0;
// 		}
		
// 		for (i = 0; i < 8; i++) {
// 			hash[i] = (hash[i] + oldHash[i])|0;
// 		}
// 	}
	
// 	for (i = 0; i < 8; i++) {
// 		for (j = 3; j + 1; j--) {
// 			var b = (hash[i]>>(j*8))&255;
// 			result += ((b < 16) ? 0 : '') + b.toString(16);
// 		}
// 	}
// 	return result;
// };
// function encrypt(plaintext,rawkey) {
// 	var textBytes = aesjs.utils.utf8.toBytes(plaintext);
// 	var key = aesjs.utils.hex.toBytes(sha256(rawkey));
	
// 	var aesCtr = new aesjs.ModeOfOperation.ctr(key, new aesjs.Counter(5));
// 	console.log(aesCtr);
// 	var encryptedBytes = aesCtr.encrypt(textBytes);

// 	var encryptedHex = aesjs.utils.hex.fromBytes(encryptedBytes);
// 	return encryptedHex;
// };
// function decrypt(encrypted,rawkey) {
// 	var encryptedBytes = aesjs.utils.hex.toBytes(encrypted);
// 	var key = aesjs.utils.hex.toBytes(sha256(rawkey));

// 	var aesCtr = new aesjs.ModeOfOperation.ctr(key, new aesjs.Counter(5));
// 	var decryptedBytes = aesCtr.decrypt(encryptedBytes);

// 	var decryptedText = aesjs.utils.utf8.fromBytes(decryptedBytes);
// 	return decryptedText;
// };
// function submitentry(form,plaintext,rawkey) {
// 	document.cookie = "cipher=" + rawkey.value + ";path=/";
// 	var e = document.createElement("input");
// 	form.appendChild(e);
// 	e.name = "e";
// 	e.type = "hidden";
// 	if (rawkey.value == ""){
// 		e.value = plaintext.value;
// 	}
// 	else {
// 		e.value = encrypt(plaintext.value,rawkey.value);
// 	};
// 	rawkey.value = "";
// 	plaintext.value = "";
// 	form.submit();
// }
function strToArrayBuffer(str) {
	var buf = new ArrayBuffer(str.length * 2);
	var bufView = new Uint16Array(buf);
	for (var i = 0, strLen = str.length; i < strLen; i++) {
	bufView[i] = str.charCodeAt(i);
	}
	return buf;
}
function arrayBufferToString(buf) {
	return String.fromCharCode.apply(null, new Uint16Array(buf));
}
function arrayBufferToHex(buf) { // buffer is an ArrayBuffer
	return Array.prototype.map.call(new Uint8Array(buf), x => ('00' + x.toString(16)).slice(-2)).join('');
}
function hexToArrayBuffer(hex) {
	var typedArray = new Uint8Array(hex.match(/[\da-f]{2}/gi).map(function (h) {
	  return parseInt(h, 16)
	}))
	return typedArray;
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}



////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
function encrypt(form,plainText,passphraseKey) {
	var saltBuffer = strToArrayBuffer('e85c53e7f119d41fd7895cdc9d7bb9dd');

	var algoKeyGen = {
	  name: 'AES-GCM',
	  length: 256
	};
	var iv = window.crypto.getRandomValues(new Uint8Array(12));
	var algoEncrypt = {
	  name: 'AES-GCM',
	  iv: iv,
	  tagLength: 128
	};
	var keyUsages = [
	  'encrypt',
	  'decrypt'
	];

	var data = window.crypto.subtle.importKey(
	  'raw', 
	  strToArrayBuffer(passphraseKey.value), 
	  {name: 'PBKDF2'}, 
	  false, 
	  ['deriveBits', 'deriveKey']
	).then(function(key) {
	  return window.crypto.subtle.deriveKey(
		{ "name": 'PBKDF2',
		  "salt": saltBuffer,
		  "iterations": 100,
		  "hash": 'SHA-256'
		},
		key,
		{ "name": 'AES-GCM', "length": 256 },
		true,
		[ "encrypt", "decrypt" ]
	  );
	}).then(function(webKey) {
		return window.crypto.subtle.encrypt(algoEncrypt, webKey, strToArrayBuffer(plainText.value));
	}).then(function (cipherText) {
		//console.log('Cipher Text: ' + arrayBufferToHex(cipherText));
		//console.log('IV: ' + arrayBufferToHex(iv));
		//console.log(arrayBufferToHex(iv)+arrayBufferToHex(cipherText));
		//return arrayBufferToHex(iv)+arrayBufferToHex(cipherText);

		document.cookie = "cipher=" + passphraseKey.value + ";path=/";
		var e = document.createElement("input");
		form.appendChild(e);
		e.name = "e";
		e.type = "hidden";
		if (passphraseKey.value == ""){
			e.value = plainText.value;
		}
		else {
			e.value = arrayBufferToHex(iv)+arrayBufferToHex(cipherText);
		};
		passphraseKey.value = "";
		plainText.value = "";
		form.submit();
	});

};
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
function decrypt(data,passphraseKey) {
	var Invec = hexToArrayBuffer(data.slice(0, 24));
	var cipherText =  hexToArrayBuffer(data.slice(24));

	var saltBuffer = strToArrayBuffer('e85c53e7f119d41fd7895cdc9d7bb9dd');

	var algoDecrypt = {
	  name: 'AES-GCM',
	  iv: Invec,
	  tagLength: 128
	};

	var data = window.crypto.subtle.importKey(
	  'raw', 
	  strToArrayBuffer(passphraseKey), 
	  {name: 'PBKDF2'}, 
	  false, 
	  ['deriveBits', 'deriveKey']
	).then(function(key) {
	  return window.crypto.subtle.deriveKey(
		{ "name": 'PBKDF2',
		  "salt": saltBuffer,
		  "iterations": 100,
		  "hash": 'SHA-256'
		},
		key,
		{ "name": 'AES-GCM', "length": 256 },
		true,
		[ "encrypt", "decrypt" ]
	  );
	}).then(function(webKey) {
		return window.crypto.subtle.decrypt(algoDecrypt, webKey, cipherText);
	}).then(function (plainText) {
		//console.log('Plain Text: ' + arrayBufferToString(plainText));
		return arrayBufferToString(plainText);
	}).catch (function (err) {
	  //console.log('Error: ' + err.message);
	  //console.log('wrong Plain Text: ' + arrayBufferToString(cipherText));
	  return arrayBufferToHex(Invec) + arrayBufferToHex(cipherText);
	});
	return data
};
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////