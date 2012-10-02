/*
    Written by Jonathan Snook, http://www.snook.ca/jonathan
    Add-ons by Robert Nyman, http://www.robertnyman.com
NB ricerca una sola classe. se si vuole ricercare elementi con pi? classi, vedi http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
*/

function getElementsByClassName(oElm, strTagName, strClassName){
    var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    strClassName = strClassName.replace(/\-/g, "\\-");
    var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
    var oElement;
    for(var i=0; i<arrElements.length; i++){
        oElement = arrElements[i];      
        if(oRegExp.test(oElement.className)){
            arrReturnElements.push(oElement);
        }   
    }
    return (arrReturnElements)
}


/*
 * modified version of ul2finder,
 * originally written by Christian Heilmann (http://icant.co.uk)
 * turns the nested list with the ID "finder" into a dynamic list
 * uses the CSS classes defined in the variables
 */
function ul2finder()
{
	// Define variables used and classes to be applied/removed
	var i,uls,als,finder,inputs,j,attr,found;
	var parentClass='parent';
	var showClass='shown';
	var hideClass='hidden';
	var openClass='open';
	//var first = 1;
	
	// check if our finder list exists, if not, stop all activities
	finders=getElementsByClassName(document,'*','explodableList');
	if(!finders[0]){return;}

	// add the class domenabled to the body
	cssjs('add',document.body,'domenabled')
//alert("liste: " + finders.length);
for(z=0;z<finders.length;z++)
{
	// loop through all lists inside finder, position and hide them 
	// by applying the class hidden
	uls=finders[z].getElementsByTagName('ul');

	for(i=0;i<uls.length;i++)
	{
		found=0;
		inputs = uls[i].getElementsByTagName('input');
		for(j=0;j<inputs.length;j++)
		{
			attr=inputs[j].getAttribute('checked');
//			alert(" " + attr);
			if (attr == 'checked') {found = 1; break;}
		}
/*		if (!found)
		{
			inputs = uls[i].getElementsByTagName('li');
			for(j=0;j<inputs.length;j++)
			{
				attr=inputs[j].getAttribute('class');
	//			alert(" " + attr);
				if (attr == 'selectedItem') {found = 1; break;}
			}
		}
*/		if(!found)cssjs('add',uls[i],hideClass);
		else cssjs('add',uls[i],showClass);
	}	

	// loop through all links of inside finder
	lis=finders[z].getElementsByTagName('li');

	for(i=0;i<lis.length;i++)
	{
		// if the li containing the link has no nested list, skip this one
		if(!lis[i].getElementsByTagName('ul')[0])
		{
				
			continue;
		}
		var newa=document.createElement('a');
		newa.href='#';
		newa.appendChild(document.createTextNode(lis[i].firstChild.nodeValue));
		lis[i].replaceChild(newa,lis[i].firstChild);
		// otherwise apply the parent class if hidden or open class if shown
		// TODO da sistemare il riconoscimento del classname
		if (lis[i].getElementsByTagName('ul')[0].className == "" + showClass) cssjs('add',newa,openClass);
		else cssjs('add',newa,parentClass);
		
	/*	if (first == 1)
		{
			cssjs('swap',newa,parentClass,openClass)
			cssjs('swap',newa.parentNode.getElementsByTagName('ul')[0],showClass,hideClass)
			first = 0;
		}
	*/	
		// if the user clicks on the link
		lis[i].getElementsByTagName('a')[0].onclick=function()
		{
		// loop through all lists inside finder
		/*	for(var i=0;i<uls.length;i++)
			{
				// avoid the list connected to this link
				var found=false;
				for(j=0;j<uls[i].getElementsByTagName('ul').length;j++)
				{
					if(uls[i].getElementsByTagName('ul')[j] == 		
						this.parentNode.getElementsByTagName('ul')[0])
					{
						found=true;
						break;
					}
				}
				// and hide all others
				if(!found)
				{
					cssjs('add',uls[i],hideClass)
					cssjs('remove',uls[i],showClass)
					cssjs('remove',uls[i].parentNode.getElementsByTagName('a')[0],openClass)
					cssjs('add',uls[i].parentNode.getElementsByTagName('a')[0],parentClass)
				}
			}	
		*/	// change the current link from parent to open 	
			cssjs('swap',this,parentClass,openClass)
			// show the current nested list 
			cssjs('swap',this.parentNode.getElementsByTagName('ul')[0],showClass,hideClass)

			// don't follow the real HREF of the link
			return false;
		}
	}	
}
	/*
	 * cssjs
	 * written by Christian Heilmann (http://icant.co.uk)
	 * eases the dynamic application of CSS classes via DOM
	 * parameters: action a, object o and class names c1 and c2 (c2 optional)
	 * actions: swap exchanges c1 and c2 in object o
	 *			add adds class c1 to object o
	 *			remove removes class c1 from object o
	 *			check tests if class c1 is applied to object o
	 * example:	cssjs('swap',document.getElementById('foo'),'bar','baz');
	 */
	function cssjs(a,o,c1,c2)
	{
		switch (a){
			case 'swap':
				o.className=!cssjs('check',o,c1)?o.className.replace(c2,c1):o.className.replace(c1,c2);
			break;
			case 'add':
				if(!cssjs('check',o,c1)){o.className+=o.className?' '+c1:c1;}
			break;
			case 'remove':
				var rep=o.className.match(' '+c1)?' '+c1:c1;
				o.className=o.className.replace(rep,'');
			break;
			case 'check':
				return new RegExp('\\b'+c1+'\\b').test(o.className)
			break;
		}
	}
}

// Check if the browser supports DOM, and start the script if it does.
if(document.getElementById && document.createTextNode)
{
	window.onload=ul2finder;
}
