<?php
/**
 * Ckforms for CK Forms Component
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Ckforms Model
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsModelCkforms extends JModel
{

	var $_data;
	
	/**
	 * Retrieves the form data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		
		$array = JRequest::getVar('id',  0, '', 'array');
		$id=(int)$array[0];
			
		$query = ' SELECT * FROM #__ckforms where id='.$id ;
						
		$this->_db->setQuery( $query );
		$this->_data = $this->_db->loadObject();

		$query = ' SELECT * FROM #__ckfields where fid='.$id." and published=1 order by ordering asc" ;
		$fields = $this->_getList( $query );
		
		$n=count($fields );
		for ($i=0; $i < $n; $i++)
		{ 
			$opt = explode("[--]", $fields[$i]->defaultvalue);
			
			switch ($fields[$i]->typefield)
			{
				case 'text':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueT = $key1[1];
					} else {
						$fields[$i]->t_initvalueT = '';
					}
					if (count($opt) > 1) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_maxchar = $key2[1];
					} else {
						$fields[$i]->t_maxchar = '';
					}
					if (count($opt) > 2) {
						$key3 = explode("===", $opt[2]);
						$fields[$i]->t_texttype = $key3[1];	
					} else {
						$fields[$i]->t_texttype = '';
					}
					if (count($opt) > 3) {
						$key4 = explode("===", $opt[3]);
						$fields[$i]->t_minchar = $key4[1];		
					} else {
						$fields[$i]->t_minchar = '';
					}
					break;
	
				case 'hidden':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueH = $key1[1];
					} else {
						$fields[$i]->t_initvalueH = '';
					}
						
					break;
					
				case 'textarea':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueTA = $key1[1];
					} else {
						$fields[$i]->t_initvalueTA = '';
					}
					if (count($opt) > 1) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_HTMLEditor = $key2[1];
					} else {
						$fields[$i]->t_HTMLEditor = '';
					}
					if (count($opt) > 2) {
						$key3 = explode("===", $opt[2]);
						$fields[$i]->t_columns = $key3[1];
					} else {
						$fields[$i]->t_columns = '';
					}
					if (count($opt) > 3) {
						$key4 = explode("===", $opt[3]);
						$fields[$i]->t_rows = $key4[1];
					} else {
						$fields[$i]->t_rows = '';
					}
					if (count($opt) > 4) {
						$key5 = explode("===", $opt[4]);
						$fields[$i]->t_wrap = $key5[1];					
					} else {
						$fields[$i]->t_wrap = '';
					}
					if (count($opt) > 5) {
						$key6 = explode("===", $opt[5]);
						$fields[$i]->t_maxchar = $key6[1];
					} else {
						$fields[$i]->t_maxchar = '';
					}
					if (count($opt) > 6) {
						$key7 = explode("===", $opt[6]);
						$fields[$i]->t_minchar = $key7[1];							
					} else {
						$fields[$i]->t_minchar = '';
					}

					break;
	
				case 'checkbox':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueCB = $key1[1];
					} else {
						$fields[$i]->t_initvalueCB = '';
					}
					if (count($opt) > 0) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_checkedCB = $key2[1];										
					} else {
						$fields[$i]->t_checkedCB = '';
					}
					break;
					
				case 'radiobutton':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_listHRB = $key1[1];
					} else {
						$fields[$i]->t_listHRB = '';
					}
					break;

				case 'select':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_multipleS = $key1[1];
					} else {
						$fields[$i]->t_multipleS = '';
					}
					if (count($opt) > 1) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_heightS = $key2[1];
					} else {
						$fields[$i]->t_heightS = '';
					}
					if (count($opt) > 2) {
						$key3 = explode("===", $opt[2]);
						$fields[$i]->t_listHS = $key3[1];					
					} else {
						$fields[$i]->t_listHS = '';
					}
					break;

				case 'button':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_typeBT = $key1[1];
					} else {
						$fields[$i]->t_typeBT = '';
					}
					break;
			}					
		
		}				

		$this->_data->fields = $fields;
		
		return $this->_data;
	}
	
	/**
	 * Save Hits
	 * @return void
	 */
	function addHits()
	{
		$dba	=& JFactory::getDBO();
		
		$ckform = $this->getData();
		
		$query = " update #__ckforms set hits = ".($ckform->hits + 1). " where id = ".$ckform->id;

		$dba->Execute($query);		
	}
	
	/**
	 * Save data
	 * @return void
	 */
	function saveData($post)
	{		
		$ckform = $this->getData();
		
		if (file_exists ($ckform->uploadpath) == true)
		{
			$nb_uploaded_file = 0;
			$n=count($ckform->fields );
			for ($i=0; $i < $n; $i++)
			{	
				$field = $ckform->fields[$i];
				if ($field->typefield == 'fileupload')
				{
					$target_fu_path = $ckform->uploadpath . basename($_FILES[$field->name]['name']); 
					
					if(move_uploaded_file($_FILES[$field->name]['tmp_name'], $target_fu_path)) {
						$uploaded_file[$nb_uploaded_file] = $target_fu_path;
						$nb_uploaded_file++;						
					}				
				}				
			}
				
		}
				
		if ($ckform->saveresult == 1) 
		{			
			$dba	=& JFactory::getDBO();
			
			$query = ' insert into #__ckforms_'.$ckform->id."(" ;
			
			$n=count($ckform->fields );
			for ($i=0; $i < $n; $i++)
			{	
				$field = $ckform->fields[$i];
				$query = $query.$field->name.",";
			}

			$query = $query."created) values(";

			$n=count($ckform->fields );
			for ($i=0; $i < $n; $i++)
			{	
				$field = $ckform->fields[$i];
				
				if ($field->typefield == 'fileupload')
				{
					$fieldValue = $ckform->uploadpath . basename($_FILES[$field->name]['name']);
				} else if (Isset($post[$field->name])){
					$fieldValue = $post[$field->name];
				} else {
					$fieldValue = '';
				}
				
				if (is_array ($fieldValue))	
			    //if (strcasecmp($fieldValue,"Array") == 0)
				{
					$arrayVal = "";
					foreach($fieldValue as $selectValue){					
						$arrayVal = $arrayVal.$selectValue.",";
					}
					if (strcasecmp(substr($arrayVal, strlen($arrayVal) - strlen(",")),",") == 0)
					{
						$arrayVal = substr($arrayVal, 0,strlen($arrayVal) - strlen(","));
					}
					$fieldValue = $arrayVal;
				}
				
				$query = $query."'".addslashes($fieldValue)."',";
			}
			
			$query = $query."'".date("Y-m-d H:i:s")."')";
			
			$dba->Execute($query);			
						
		}		
		
		if ($ckform->emailresult == 1) {
	
			$mail =& JFactory::getMailer();
			$mail->CharSet = "utf-8";
		
			$mailBody = "Form : ".$ckform->title." [".$ckform->name."]<br/>\n";
			$mailBody = $mailBody."registered at ".date("Y-m-d H:i:s")."<br/><br/>\n\n";

			$n=count($ckform->fields );
			for ($i=0; $i < $n; $i++)
			{	
				$field = $ckform->fields[$i];
				if (Isset($post[$field->name]))
				{
					$fieldValue = $post[$field->name];
				} else 
				{
					$fieldValue = '';
				}
				
			    if (is_array ($fieldValue))	
				{
					$arrayVal = "";
					foreach($fieldValue as $selectValue){					
						$arrayVal = $arrayVal.$selectValue.",";
					}
					if (strcasecmp(substr($arrayVal, strlen($arrayVal) - strlen(",")),",") == 0)
					{
						$arrayVal = substr($arrayVal, 0,strlen($arrayVal) - strlen(","));
					}
					$fieldValue = $arrayVal;
				}
				
				$mailBody = $mailBody.$field->name . " : " . $fieldValue . "<br/>\n";
			}	
			
			if (strcmp($ckform->emailto,"") != 0)
			{
				$mail->addRecipient( explode(",", $ckform->emailto) );
			}
			if (strcmp($ckform->emailcc,"") != 0)
			{
				$mail->addCC( explode(",", $ckform->emailcc) );
			}
			if (strcmp($ckform->emailbcc,"") != 0)
			{
				$mail->addBCC( explode(",", $ckform->emailbcc) );
			}
			
			$mail->setSender( array( $ckform->emailfrom, "" ) );
			$mail->setSubject( $ckform->subject );
			$mail->setBody( $mailBody );

			$mail->IsHTML (true);
			
			if (Isset($nb_uploaded_file))
			{
			for ($i=0; $i < $nb_uploaded_file; $i++) {
				$mail->addAttachment($uploaded_file[$i]);
			}
			}
			
			$sent = $mail->Send();
			
		}		
		
	}

}
