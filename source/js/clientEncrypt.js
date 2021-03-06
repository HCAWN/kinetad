
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

function textEntry(form,inputdata,passphraseKey){
	if (inputdata.value != "") {
		newEncrypt(form,inputdata.value,passphraseKey).then(function(encrypted){
			var e = document.createElement("input");
			form.appendChild(e);
			e.name = "textdata";
			e.type = "hidden";
			e.value = encrypted
			passphraseKey.value = "";
			inputdata.value = "";
			form.submit();		
		})
	}
	else {
		console.log('empty submission - ignoring');
	};

}
function imageEntry(form,imgdata,passphraseKey){
	newEncrypt(form,imgdata,passphraseKey).then(function(encrypted){
		var e = document.createElement("input");
		form.appendChild(e);
		e.name = "imagedata";
		e.type = "hidden";
		e.value = encrypted
		passphraseKey.value = "";
		imgdata = "";
		form.submit();
	})
}

////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
function newEncrypt(form,inputdata,passphraseKey) {
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
		return window.crypto.subtle.encrypt(algoEncrypt, webKey, strToArrayBuffer(inputdata));
	}).then(function (cipherText) {

		document.cookie = "cipher=" + passphraseKey.value + ";path=/";
		if (passphraseKey.value == ""){
			console.log('raw')
			return inputdata;
		}
		else {
			console.log('not raw')
			return arrayBufferToHex(iv)+arrayBufferToHex(cipherText);
		};
	});
	return data

};
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

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