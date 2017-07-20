<?php 
/*
	// group data set doc

	expected parameters for group data set entries when editting for generaldatamodule

	'group'.$curgroupcount.'_entryminimum' this index represents the minimum amount of 
	entries expected for the current groupdataset being processed, this means that for 
	the number of new and previous entries to be processed, at the end of operations, 
	the minimum number of entries present in the database must be the value this index 
	carries. e.g, 
	if in a groupset, there are four old entries and 3 new entries, and say the user 
	deletes the current four new entries via the edit form provided, if the entryminimum 
	index value	is 5 and there are only three new entries, what happens here is that only 
	two out of the four previous entries will be deleted and there will be 5 entries left 
	in total

	'group'.$curgroupcount.'_cedit' or ''.$groupname.'_cedit' this index carries the 
	current number of edittable 
	entries present in the groupdataset being processed

	'group'.$curgroupcount.'_status'.$xt.' this index represents the status for marking 
	an entry as inactive or simply deleting it from the
	set of values registered to the current groupdataset.

	// Content concerning setting up a gd module form system	
	// things to note
	1. any change to the datamap will affect the current entry and the proper parsing of 
	previous entries especially if the change occurs in previous group content or removal
	of a groupset from the forms structure
	



*/
/*
    advanced formmontioring and validation for generic forms
    *tinymce textareas must have a data-mce="true" attribute value for focus to be
    transfered to the mce field directly
    
    sentinel elements for groups can have the following additional attributes
    data-valset="1,2,3" this is comma seperated list of element values that 
    must be provided by default on a group set that requires at least one entry provided
    on the form
    data-valcount="1-n" where n is a number this is used to control the total amount
    of expected entries from a groupset in a form, it defaults to 1 when not defined
    and data-valset has valid values.
    
    data attributes on edit form elements obtained from the group elements
    
    1. data-form-edit="true" this attribute is for form file elements in groupset
      once set to "true" the validation for the element is ignored on the edit form

    2. data-edittype="true" this attribute is for single form elements in the edit
     form, it basically does the same thing as the aforementioned data-edittype but 
     addresses only single elements in the form

    3. data-mce="true" setting this attribute on a textarea field that uses tinymce 
    would ensure that the tinymce frame created for the textarea element is given focus

    <input type="hidden" name="extraformdata" value="collagecolourtype-:-select<|>
      contenttitle-:-input<|>
      contentpost-:-textarea-:-[
        validationtype-|-fieldname-|-type-|-value>|<
        group-|-fieldname-|-type-|-value-|-fieldname-|-type-|-value
      ]<|>(validataion type are as follows
        all,single, group
      )
      egroup|data-:-[testmultiplevaliddata>|< (this part is for grouped content 
      to be validate, it is basically the name of the input element that holds the value
      of the current number of entries available in the form) 
        linktitlea-|-input>|<
        linktitleb-|-input>|<
        linktitlec-|-select
      ]-:-[1,2,3] OR [1-2,2-3,3-1] E.T.C
      this system is Javascript based and is triggered by
      using a submit button with value of "submitcontenttwo"
      a hidden input field with the name formdata also must be present
      and it must hold the name of the form currently being assesed
      "/>
      <input type="hidden" name="errormap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
      
      <input type="hidden" name="placeholdermap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
      <input type="hidden" name="errormap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
	-:-[single-|-dogallery-|-select-|-*any*-|-galimage]
	//full doc on creation of the extraformdata map
	The general format for the map is actually fielname-:-fieldtype
	but it goes further than that,
	basically the functionality gets extended to accomodate file type field values
	and dependency values 
	file type files:
	imagefield-:-input|image - specifies that only images are allowed for the field
	imagefield-:-input|image|jpeg - specifies that only images with the 'jpeg' extension
									only, 'jpg' or any other will not be valid
	imagefield-:-input|image|jpeg,jpg,png - specifies multiple extensions for the
											current field
	this concept can be applied to other filetypes.
	for unique types, 
	imagefield-:-input|other|exe,cs e.t.c

	for dependencies on other elements WITHIN the same form
	e.g
	for a single external element value
	fieldname-:-fieldtype-:-[group-|-elementname-|-elementtype-|-value]
		in egroup|data fieldsets as an entire group
		[fieldname-|-fieldtype]-:-(water/group)fall[conditionblock section]-:-
		[single-|-fieldname-|-fieldtype-|-value-|-nameoffieldtofocuson]
	
	for a single group element value:
	[fieldname-|-fieldtype-|-(group-*-fieldname-*-fieldtype-*-value)]

	further use of the extended condition section includes accepting several valid
	values for to allow more tests to be carried out on the entry.

	for a single elements dependent on multiple values of another element:
	fieldname-:-fieldtype-:-[group-|-elementname-|-elementtype-|-value1:*:value2...:*:valuen]

	for group values
	[fieldname-|-fieldtype-|-(group-*-fieldname-*-fieldtype-*-value1:*:value2...:*:valn)]

	other data validation features are:
	for form attributes,
	data-pvalidate="true" specifies if the password validation system is to be utilised
	this allows a field to be validated via regex based on prespecified data such as
		data-pvcount="number" expected number of characters in password
		data-pvcfieldname="fieldname" this field specifies if a comparison validation
		should be carried out on another field when the current password field
		has been validated

	data-evalidate="true" specifies if the current field is to be validated as an email
	
	data-cvalidate="true" specifies if the current field value is to be compared
	to another, usually a hidden field extra compulsory parameters are,
		data-element-data="fieldname-:-fieldtype" 

*/		

/*
    //	group set entries section
	basic construction for extragroup|data / egroup|data content
    for group set entries, the following must be made available to allow
    the groupset handler functions form parser work with the group. 
    The example groupname here is 'mygroupd'.
    Note groupnames in forms must be unique to avoid collision with each others data.

	1. a div with the class "groupname-field-hold" and attribute 'data-groupid="n"' 
		where 'n' represents a number pertaining to the position of the group in the 
		form. It acts as the main parent of the current group e.g 
		<div class="col-md-12 mygroupd-field-hold" data-groupid="1"></div>

	2. a sentinel field which has its name in the form 'groupnamecount'
		and is usually hidden
		e.g <input type="hidden" name="mygroupdcount" value="an integer value"/>.
		compulsory attributes for this field are data-counter="true"
		other attributes include
		data-valcount="n" 'n' is an integer, this field represents the minimum
							number of entries for the current groupset
		data-valset="1,2,3...,n" this attr follows the data-valcount attr, it indicates 
		the default validation test that must be passed in each entry for the minimum
		amount of group entries. e.g, a data-valset="1,2" means that for each group of
		compulsory entries, the first and second elements indicated in the 'egroup|data'
		map must have a value in them otherwise an error is raised.
		if this value is greater than 0, a corresponding hidden input field with the name
		'groupname_entryminimum' e.g (mygroupd_entryminimum), carrying the corresponding 
		values of the data-valcount attribute as its value must be created in order to
		preserve you minimal entry.
		In addition, a field with the name 'groupname_cedit' e.g (mygroupd_cedit), is
		expected when there are previous content being displayed for the group,
		the value of the field is the total amount of edittable entries available
		this value allows the gdmoduleparser handle updates and new entries to the 
		current groupset properly.

	3. a counter trigger element responsible for triggering the increment,decrement of
		the entries in the group. this element is usually an hyperlink tag 'a' and has
		the following attributes:
		data-name="sentinelname_addlink" e.g data-name="mygroupdcount_addlink"
		
		data-limit="n" 'n' is an integer, this attribute controls the maximum number of
		entries that can be added to the groupset leaving it empty means there is no limit
		
		data-form-name="the name of the parent form" this attr is optional

		data-i-type="default|before|after" this attr controls the way the new content in
		form are inserted, 'default' and 'before' do the same thing, but after uses the
		insertAfter jQuery method to place the new content after the marker element.
		leaving this field empty sets it value to 'default'.

		consult the mylib.js file multipleElGenerator function for more info. located at
		$HOST/scripts/mylib.js   
		
		data-sentineltype="" this field holds the current set of edittable entries for
		a groupset, basically this field is used when there is already data for the 
		groupset.

		an example of this trigger element is as follows:

		this version makes use of the multipleElGenerator function
		it works well when the groupcount sentinel element is an adjustable field
		of number value either select or number.
		<a href="##" data-name="mygroupdcount_addlink" data-i-type data-limit="20" 
		onclick="multipleElGenerator
		('form[name=parentformname] a[data-name=mygroupdcount_addlink]',
		'',
		'form[name=parentformname] div.mygroupd-field-hold',
		$('form[name=parentformname] div.mygroupd-field-hold .multi_content_hold').length,
		$('form[name=parentformname] input[name=mygroupdcount]').val(),
		'form[name=parentformname] input[name=mygroupdcount]')" 
		class="">
    	<i class="fa fa-arrow-right"></i>
		</a>

		or
		
		this version makes use of the builtin attribute selector 
		'data-type="triggerformaddlib"' to function. This version can only be used
		to add content, not remove them it works when the groupcount sentinel element
		is a hidden field this version must be created within the 'groupname-field-hold'
		element just after the 'entrypoint'.
		<a href="##" class="generic_addcontent_trigger" 
			data-type="triggerformaddlib" 
			data-name="mygroupdcount_addlink" 
			data-i-type="" 
			data-limit="10"> 
			<i class="fa fa-plus"></i>Add More?
		</a>

	4. an element,usually a div and often located as the last/first element in the 
		"groupname-field-hold", and having the following attributes:
		 name="groupnameentrypoint" e.g name="mygroupdentrypoint"

		 data-marker="true" indicating the element to be a marker for performing
		 insertions of new content into the groupparent element

		full example:	<div name="mygroupdentrypoint" data-marker="true"></div>

	5. inside the "groupname-field-hold" div, a div with the class 'multi_content_hold' 
		which represents the element holding a single groupset, the first of these 
		elements must have the following attributes:
		data-type="triggerprogenitor" meaning this is the basic build for consequent new
		groupset entries

		data-cid="1...n" this also means that it is the template set and all other entries are a 
		basic increment of the value here.
		data-name="groupname" e.g data-name="mygroupd"

					
	6. inside the 'multi_content_hold' section, a H4 tag with the class
		'multi_content_countlabels' with text content of the form 
		'entry title text (Entry 1)'
		the numeric value in the text is used in subsequent entries to indicate
		the number for those entries. so upon triggering a new set entry, the 
		value of the 'multi_content_countlabels' section in it would be 
		'entry title text (Entry n)' where 'n' is an integer usually greater than 
		one. e.g <h4 class="multi_content_countlabels">MyGroupD (Entry 1)</h4>
	
	7. after the groupsetfields have been created a simple datamap in the following format
		is expected, this map is stored in a hidden field and its name is in the form
		'name=groupnamedatamap' e.g
		A sample groupset with three fields
			1. <input name="galimage1..n" type="file"/>
			2. <input name="caption1...n" type="text"/>
			1. <textarea name="details1...n"></textarea>
		datamap for current groupset:
		<input name="mygroupddatamap" 
				type="hidden" 
				data-map="true" 
				value="galimage-:-input<|>
						caption-:-input<|>
						details-:-textarea"/>
		Note that the map values representing the field names have no number attached to
		them the numbers representing their count will be given to them when the new
		element set are created.
		
	Optional content for the egroup|data group set entries include
	
	1.	A hidden input field with the name 'funcdata' carrying a json string value 
		in the form of:

		{
		 func:array,
		 selectors:array,
		 typegd:array,
		 params:multidimarray
		 dselectors:array,
		 dtypegd:array,
		 dparams:multidimarray,
		}
		this json data is used to tell the parsing function that you have content that
		needs certain methods to be re-initialised on them and give a description on
		how to reinit them even allowing you specify if content needs to be destroyed
		first.
		The expected indexes are 
		
		'func' - array, this represents the method to be called on a selector
	    
	    'selectors'|'dselectors' - array, this represents the selectors the method is 
	    to be called on
	    
	    'typegd' - array, this represents the nature of the parameters sent to the method
	    specifying if they are encapsulated in parentheses from the start e.g 
	    .select2({}) or are just plain e.g .select2('');
	    
	    'params'|'dparams' - array this represents the parameters to be passed into the
	    method being called the values here are created singular or in twos, meaning that 
	    everytwo values represents a potential key and value pair in the method or
	    a single value pair, depending on the corresponding 'typegd' value provided.
	    if the 'typegd' value is 'plainjq' then the params value expected is singular
	    i.e params:[['destroy']] is valid, otherwise 'encapsjq' then the value expected
	    are in twos i.e params:[['theme','bootstrap']] or params:[['theme','']] is valid
	    as an entry, the key here is 'theme' and the value for it is 'bootstrap', the
	    corresponding output here:  something.select2({theme:bootstrap});
		Note that if you want 'bootstrap' passed as string and not a variable
		you would have you content in the form '``bootstrap``' the `` is replaced with
		a singlequote while ~~ is replaced with a double quote. This replacements are
		only done for 'encapsjq' entries.
		below is an example:

		{
		 func:['select2'],
		 selectors:['select[data-name-faselect]'],
		 typegd:['encapsjq'],
		 params:[['theme','bootstrap','templateResult','faSelectTemplate']]
		 delectors:['select[data-name-faselect]'],
		 dtypegd:['plainjq'],
		 dparams:[['destroy']]
		}
		the params|dparams section stores values a bit differently
		if the typegd value for a set is encapsjq(encapsulated jequ) 
		to put in quotes with in the param array value portion string


		a script is generated at the end of the addition process that will carryout the 
		actions you desire

	The design of the groupsets or how they are presented is of no import as long as
	the aforementioned rules are followed.


*/

/*
	content for the gdmoduledataeditdefault
	1. All fields have variable names which correspond to their original form element name
		and carry their database values
	2. All select fields have a corresponding javascript generated with their output that 
	carries	their selector property and sets their value to the current value of their 
	corresponding variable
	3. ALL file fields have their corresponding related information stored in an array of
	fieldname_filedata, and have their form ready id and delete options in the array index
	of fieldname_filedata['idoutput'] and fieldname_filedata['deleteoutput'] and an index
	combining them both fieldname_filedata['manageoutput']
	the full list of the _filedata index is as follows
	['id'] - the id of the current file in the media table
	['mediatype'] - the type of the media, audio video image document etc

	['location'] - the path where the unaltered file resides 
	
	['medsizes'] - 	this path is for image files, it is the path to the medium sized 
					version of the uploaded image 
	
	['thumbnail'] - this path is also for image files, it carries the thumbnail version 
					of the image
	
	['title'] - a simple title for the file if any
	
	['preview'] - this portion stores the path rto the preview file in cases of video and 
				  audio stored locally on the server
	
	['details'] - the details foer the current entry if any
	
	['width'] - the width of the image file, the original
	
	['height'] - the height of the image file, the original
	
	['filesize'] - the size of the original uploaded file
	
	['idoutput'] - the input form element carrying the id of the current field entry
					and parseable by the gdmodule
	['deleteoutput'] -  the select form element carrying the option for deleting the 
						current file entry using the gdmodule
	['manageoutput'] - this field carries a combination of the id and delete output

	4. A group array variable $gd_general_array is available and functions in the format
	 $gd_general_array["fieldname"] which carries the value of the field, this array 
	 provides "ALL" field values for any _gdunitform. It even carries 'maintype' and
	 'subtype' values as well
*/

/*
	content for creating _gdunit display request elements
	json string must contain the following key properties
	'vt' - viewtype values are 'create' or 'edit' or 'somethingcustom'
	'mt' - maintype this represents the maintype value for the current data
			in the generalinfo table
	'pr' - processroute, this represents the path to the file that handles this current
			entry. The path is always relative from the root of the site/app
	'vnt' - variant/entryvariant, this is the value of the entryvariant element in forms
			found in the process route this value is used mainly by the file the form
			is being submitted to. the possible values here are 'contententry' and 
			'contententryupdate'

	optional key properties are:
	'st' - subtype this represents the parent type of the maintype value, it may seem 
			counter intuitive but this is an accurate representation of the parent-child
			relationship in the database. The maintype for an entry actually determines 
			a lot of activites that can be done on it while the subtype is mainly there 
			for grouping purposes.	
	'er' - editroute, this value represents the route to the file responsible for handling
			the maintype defined, if no value is given it defaults to the value of the 
			process route.
	'preinit' -  specifies if the handling of the generalinfo function will occur outside
				the specified process route in the display function or not, values are
				'true' 'false' or an array of 'true'/'false' the array must be equal to
				the 'mt' index
	'actionpath' -  this specifies the path to a handler file that would manage form
					content at the process route or edit route path. The values here
					must coincide with the number of maintype 'mt' entries i.e
					if 'mt' is an array then action path if any will be the same as
					the content count. The path is always relative from the root of the 
					site/app
	'ugi' - usegeneralinfo. this specifies whether the generalinfo function is to be used. 
			In the event of a 'vt':"edit" value being present or not, its values are 
			'true'|'false'.
	
	the json data is expected to be the value of the 'appdata-datamap' attribute in the 
	admin menu or 'data-edata' attribute in the adminoutput table entry edit-link or 
	the value of the input[name=datamap] element in the meneame pagination section in the
	adminoutput table entries display.
*/

/*
	function creation rules
	1. 
*/
?>