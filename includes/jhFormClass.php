<?php
  /*
  *
  * CLASS FORM
  * 
  * This class handles all form loads, edits, and save/updates and lists
  * Based on bootstrap horizontal form display
  * 
  * This class depends on the Zebra_Database class from https://github.com/stefangabos/Zebra_Database
  * which is used for all form management DB requests. it should be loaded an intialized at the beginning of page load.
  */
 
class jhForm 
{
    public $validate = false;
    public $debug = false;
    public $recordId=0;
    public $dbTable='';
    public $whereConditions=array();
    public $listFields=array();
    public $foreignId=0;
    public $foreignKeyField='';
    public $formActionHTML=array();
    
    private $formAction='list'; // options are add, edit, delete and list
    private $formFields=array(); // holder for all created fields
    private $loadingRecord=false;
    private $formName = '';
    private $validationRules=array();
    private $updateRecord=false;
    private $formHtml = '';
    private $validationHtml = '';
      
    public function __construct($name='',$handleimages=false)
    {
        if($name=='')
        {
            $name='jhForm_'.time();
        }
        if($_GET['action'])
        {
            $this->formAction=$_GET['action'];
        }
        if($_GET['id'])
        {
            $this->recordId=intval($_GET['id']);
        }
        if($_GET['fid'])
        {
            $this->foreignId=intval($_GET['fid']);
            $this->addFormAction('','add','Add new record',false,true); //out of the box, add a 'add new record' form action, with foreign key if present
        } else {
            $this->addFormAction('','add','Add new record',false,false); //out of the box, add a 'add new record' form action, with foreign key if present
        }
        
        $this->addId(); //add a default database column named id
    
        $this->formName=$name;
        $this->formHtml = "
        
        \n\n<form method=post id='$name' name='$name'".($handleimages!=''? "encytype='multipart/form-data'" : '')." class='form-horizontal'>\n";
        
        //add alerts to the page for form updating notifications -- base bootstrap functionality
        ?>
        <div id='jhFormAlertMain' class="page-alert" style='top:40px;display:none;'>
         <div id='jhFormAlertClass' class="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span id='jhFormAlertMessage'></span>
          </div>
        </div>
        <script>
        function setJHFormAlert(type,message)
        {
            $('#jhFormAlertClass').addClass('alert-'+type);
            $('#jhFormAlertMessage').html(message);
            
            if(type!='warning')
            {
                window.setTimeout(function() {
                    $("#jhFormAlertClass").slideUp(500, function(){
                        $(this).remove(); 
                    });
                }, 3000);
            }
            $('#jhFormAlertMain').show();
            $('#jhFormAlertClass').alert();    
        }
        
        </script>
        <?php       
        }
    
    public function loadRecord()
    {
        global $db;
        $this->loadingRecord=true;
        
        $cols='';
        foreach($this->formFields as $field)
        {
            $cols.=$field['dbField'].',';
        }
        //$cols=substr($cols,0,strlen($cols)-1);
        $cols=rtrim($cols,',');
        
        $criteria['WHERE']=array('id'=>$this->recordId);
        if(isset($whereConditions))
        {
            foreach($whereConditions as $key=>$value)
            {
                $criteria[$key]=$value;
            }
        }
        $db->select(
            $cols,
            $this->dbTable,
            'id = ?',
            array($this->recordId)
        );
        $db->show_debug_console();
        // after this, one of the "fetch" methods can be run:

        // to fetch all records to one associative array
        $records = $db->fetch_assoc_all();
        if($db->returned_rows>0)
        {
            $dbfields=$records[0];
            $tempFields=array();
            foreach($this->formFields as $field)
            {
                $field['dbValue']=$dbfields[$field['dbField']];
                $tempFields[]=$field;
            }
            $this->formFields=$tempFields;
        }
        if($this->debug)
        {
            print "<h3>Database Records</h3>";
            print "<pre>\n";
            print_r($this->formFields);
            print "</pre>\n"; 
        }   
            
    }
    
    public function setForeignKey($fkField)
    {
        /*
        *  this function sets a foreign field id. So if this record relates to another record, it will be by this field
        */
        $this->formFields[$fkField]=array('type'=>'hidden','label'=>'','dbField'=>$fkField,'help'=>'','postValue'=>$this->foreignId,'dbValue'=>$this->foreignId);
    }
    
    private function addId()
    {
        $this->formFields['id']=array('type'=>'hidden','label'=>'ID','dbField'=>'id','help'=>'','postValue'=>$this->recordId,'dbValue'=>$this->recordId);
    }
    
    public function addHidden($field,$value='')
    {
        /*
        *  adds a hidden field to the form
        */
        $this->formFields[$field]=array('type'=>'hidden','label'=>'','dbField'=>$field,'help'=>'','postValue'=>$value,'dbValue'=>$value);
    }
    
    
    public function addText($label,$field,$placeholder='',$helpText='',$validation=array()) 
    {
      $this->formFields[$field]=array('type'=>'text','label'=>$label,'dbField'=>$field,'help'=>$helpText,'postValue'=>'','dbValue'=>'');
        
      if(isset($validation))
      {
          $this->setValidation($field,$validation);    
      }
      
    }
    
    private function setValidation($field,$validation)
    {
      $this->validate=true;
      $rule['field']     = $field;    
      $rule['fieldtype'] = (isset($validation['fieldtype'])? $validation['fieldtype']: 'text');
      $rule['minlength'] = (isset($validation['minlength'])? $validation['minlength']: 2);
      $rule['maxlength'] = (isset($validation['maxlength'])? $validation['maxlength']: '');
      $rule['max']  = ($validation['max']!='')? $validation['max']: '';
      $rule['min']  = ($validation['min']!='')? $validation['min']: '';
      $rule['equalTo']  = ($validation['equalTo']!='')? $validation['equalTo']: '';
      
      if($validation['required']) $rule['required']='true';
      if($validation['fieldtype']=='email') $rule['email']='true';
      if($validation['fieldtype']=='number') $rule['number']='true';
      if($validation['fieldtype']=='url') $rule['url']='true';
      if($validation['fieldtype']=='creditcard') $rule['creditcard']='true';
      
      $this->validationRules[]=$rule;    
       
    }
    
    private function getValidationHtml()
    {
        $vRules='';
        
        foreach($this->validationRules as $vr)
        {
            $vRules.=$vr['field'].": {";            
      if($vr['minlength']!=''){$vRules.="
      minlength: $vr[minlength]";}
      if($vr['maxlength']!=''){$vRules.=",
      maxlength: $vr[maxlength]";}
      if($vr['required']=='true'){$vRules.=",
      required: true";}
      if($vr['email']=='true'){$vRules.=",
      email: true";}
      if($vr['creditcard']=='true'){$vRules.=",
      creditcard: true";}
      if($vr['number']=='true'){$vRules.=",
      number: true";}
      if($vr['url']=='true'){$vRules.=",
      url: true";}
      if($vr['equalTo']!=''){$vRules.=",
      equalTo: '#$vr[equalTo]'";}
      if($vr['min']!=''){$vRules.=",
      min: $vr[min]";}
      if($vr['max']!=''){$vRules.=",
      max: $vr[max]";}
      $vRules.="      
    },
    ";    
        }
          
        $vRules=rtrim($vRules,",");
        $this->validationHtml="
<script type='text/javascript'>
\$.validator.setDefaults({
    submitHandler: function(form) {
        form.submit();
    }
});
\$(document).ready(function(){
 \$('#".$this->formName."').validate(
 {
  rules: {
    ".$vRules."
  }
 });
}); // end document.ready
</script>
        ";
         return $this->validationHtml;
    }
    
    public function render()
    {
        //here we will toggle based on if this is a post or not
        if($_POST)
        {
            $this->handlePost();
        } else {
            switch($this->formAction)
            {
                case "delete":
                    $this->deleteRecord();
                break;
                
                case "list":
                    $this->listView();
                break;
                
                default:
                    //always try to load form data if there is a record id set
                    if($this->recordId){$this->loadRecord();}
                    $this->renderHTML();
                    $formCode=generate_random_string('32');
                    $_SESSION['formCode']=$formCode;
                    $buttons="
    <div class='control-group'>
        <div class='controls'>
              <input type='submit' class='btn btn-primary' value='Save' />
              <a href='?action=list' class='btn'>Cancel</a>
        </div>
    </div>
    ";
            $formInfo="
    <input type='hidden' name='dbload' value='".$this->loadingRecord."' />
    <input type='hidden' name='formCode' value='$formCode' />
            ";
                    $this->formHtml.=$buttons.$formInfo."</form>\n";
                    echo $this->formHtml;
                    if($this->validate)
                    {
                        echo $this->getValidationHtml();
                    }
                break;
            }
        }
    }
    
    private function renderHTML()
    {
        foreach($this->formFields as $field)
        {
            $type=$field['type'];
            $name=$field['dbField'];
            $label=$field['label'];
            $placeholder=$field['placeholder'];
            $descriptor=$field['help'];
            if($_POST)
            {
                $value=$field['postValue'];
            } else {
                $value=$field['dbValue'];
            }
            switch($field['type'])
            {
                case "text":
                $fieldHtml='
    <div class="control-group">
       <label class="control-label" for="'.$name.'">'.$label.'</label>
       <div class="controls">
         <input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$placeholder.'" value="'.$value.'" />
       </div>
    </div>
    ';
                break;
                
                case "hidden":
                $fieldHtml='
    <input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" />';
                break;
            }
            $this->formHtml.=$fieldHtml;
        }
    }
    
    
    public static function sanitizeFields($post)
    {
        foreach($post as $key => $value){
            if(is_array($post[$key])){
                $post[$key] = $this->sanitizeFields($post[$key]);
            }else {
                $post[$key] = stripslashes($value);
            }
        }
        
        return $post;
    }
    
    private function handlePost()
    {
        global $db;
        //here we process the form.
        //first step is to validate that the formCode matches the one in the SESSION
        if($_POST['formCode']===$_SESSION['formCode'])
        {
            //check if this form was loaded from the database
            if($_POST['dbload']){$this->updateRecord=true;}
            //loop through all fields and create an array of values
            $tempFields=array();
            foreach($this->formFields as $field)
            {
                switch($field['type'])
                {
                    case 'checkbox':
                        if($_POST[$field['dbField']])
                        {
                            $field['postValue']=1;
                        } else {
                            $field['postValue']=0;
                        }
                    break;
                    
                    default:
                      $field['postValue']=$_POST[$field['dbField']];
                    break;
                }
                $tempFields[]=$field;
            }
            $this->formFields=$tempFields;
            if($this->debug)
            {
                print "<h3>POST array</h3><pre>";
                print_r($_POST);
                print "</pre>\n";
                
                print "<h3>Posted Form</h3><pre>";
                print_r($this->formFields);
                print "</pre>\n";
            }
            $uFields=array();
            foreach($this->formFields as $field)
            {
                $uFields[$field['dbField']]=$field['postValue'];
            }
            
            if($this->updateRecord)
            {
                if($db->update($this->dbTable,$uFields,'id=?',array($this->recordId)))
                {    ?>
                    <script>
                    setJHFormAlert('success','The record was updated successfully')
                    </script>
                    <?php
                } else {
                       ?>
                    <script>
                    setJHFormAlert('error','There was a problem updating the record')
                    </script>
                    <?php
                
                }
            } else {
                if($db->insert($this->dbTable,$uFields))
                {  ?>
                    <script>
                    setJHFormAlert('success','The new record was inserted successfully')
                    </script>
                    <?php
                } else {
                       ?>
                    <script>
                    setJHFormAlert('error','There was a problem inserting the record')
                    </script>
                    <?php
                }
            }
            ?>
            <script>
            window.history.pushState("JHFormManager", "List view", "?action=list");
            </script>
            <?php
            $this->listView();
        } else {
            if($this->debug){print "Attempted form injection you bad guy you :)";}
        }     
    }
    
    private function listView()
    {
        global $db;
        $tableColumns=1;
        
        if(!in_array('id',$this->listFields)){array_unshift($this->listFields,'id');}
        
        $cols=implode(",",$this->listFields);
        $db->select(
            $cols,
            $this->dbTable,
            '',
            array(),
            '',
            500
        );
        $db->show_debug_console();
        
       ?>
<div class='row'>
    <div id='tableArea' class='span9'>
        <table id='listRecords'>
        <thead>
            <?php
                    foreach($this->listFields as $key=>$lf)
                    {
                        //if($this->debug){print "<ul>Checking $lf against";}
                        foreach($this->formFields as $ff)
                        {
                            if($ff['dbField']==$lf)
                            {
                                ?>
            <th><?php echo $ff['label']?></th><?php
                               //if($this->debug){print "<li>$ff[dbField] - Found </li>";}
                            
                            }
                            //if($this->debug){print "<li>$ff[dbField] - not found </li>";}
                            
                        }
                        //if($this->debug){print "</ul>";}
                    } 
                ?>
            <th>Record Actions</th>
        </thead>
        <tbody>
        <?php
                // to fetch all records to one associative array
                $records = $db->fetch_assoc_all();
                if($db->returned_rows>0)
                {
                    foreach($records as $record)
                    {
                        $recordid=$record['id'];
                        ?>
              <tr>
                        <?php
                            foreach($this->listFields as $key=>$lf)
                            {
                             ?>
                    <td><?php echo $record[$lf]; ?></td>
                            <?php
                            } 
                        ?>
                    <td>
                        <div class='btn-group'>
                          <button type='button' onclick='window.location="?action=edit&id=<?php echo $recordid; ?>"' class='btn btn-small btn-primary'>Edit</button>
                          <button type='button' data-dest='?action=delete&id=<?php echo $recordid; ?>' class='btn btn-small btn-warning delete'>Delete</button>
                        </div>
                    </td>
              </tr> 
                    <?php
                    }
                    ?>
              
                    <?php
                        
                }
               
                ?>
         </tbody>
        </table>
    </div>
    <div id='tableInformation' class='span3 well'>
    <h4>Actions</h4>
        <?php print implode("\n",$this->formActionHTML); ?>
    </div>
</div><!-- closing row -->
<script type='text/javascript'>
    $(document).ready(function() {
        $('#listRecords').dataTable({
            
        });
        $(".delete").click(function(event){
            var dest=$(this).data('dest');
            bootbox.confirm("Are you sure you want to delete this record?", function(result) {
                event.preventDefault();
                event.stopPropagation();
                if (result==true) {                                             
                    window.location=dest;
                } else {
                    event.preventDefault();                        
                }
                
            });
        });
        
    });
</script>
     <?php  
    }
    
    public function addFormAction($script='',$action='add',$label='Add new record',$btnClass='',$appendRecordId=false,$appendForeignId=false,$returnToParent=false)
    {
        if($action=='add'){$btnClass='btn-primary';}
        $this->formActionHTML[]="    <a href='$script?action=$action' class='btn btn-block $btnClass'>$label</a><br />\n";
    }
}


?>