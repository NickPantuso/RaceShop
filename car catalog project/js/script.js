let nPass = document.getElementById('nPass');
nPass.addEventListener('input', validate);

let vPass = document.getElementById('vPass');
vPass.addEventListener('input', validate);

function validate(event)
{
	let numEx = /^.*[0-9].*$/;
	let charEx = /^.{8,}$/;
	let pNum = document.getElementById('pNum');
	let pChar = document.getElementById('pChar');
	let pMatch = document.getElementById('pMatch');
	let elID = event.srcElement.attributes['id'].nodeValue;
	let submit = document.getElementById('submit');
	
	if(elID == 'nPass')
	{
		if(!numEx.test(nPass.value) && !charEx.test(nPass.value))
		{
			pNum.style.display='block';
			pNum.innerText = 'Password must contain a number.';
			pChar.style.display='block';
			pChar.innerText = 'Password must be 8 characters long.';
		} 
		else if(numEx.test(nPass.value) && !charEx.test(nPass.value))
		{
			pNum.style.display='none';
			pNum.innerText = '';
			pChar.style.display='block';
			pChar.innerText = 'Password must be 8 characters long.';
		} 
		else if(!numEx.test(nPass.value) && charEx.test(nPass.value))
		{
			pNum.style.display='block';
			pNum.innerText = 'Password must contain a number.';
			pChar.style.display='none';
			pChar.innerText = '';
		}
		else if(numEx.test(nPass.value) && charEx.test(nPass.value))
		{
			pNum.style.display='none';
			pNum.innerText = '';
			pChar.style.display='none';
			pChar.innerText = '';
		}
	}
	
	if(elID == 'vPass')
	{
		if(vPass.value != nPass.value)
		{
			pMatch.style.display='block';
			pMatch.innerText = 'Password and \'Verify Password\' do not match.';
		} 
		else if(vPass.value == nPass.value)
		{
			pMatch.style.display='none';
			pMatch.innerText = '';
		}
	}
	
	if(numEx.test(nPass.value) && charEx.test(nPass.value) && vPass.value == nPass.value)
	{
		submit.disabled = false;
	}
	else
	{
		submit.disabled = true;
	}
}