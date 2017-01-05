
<?php
global $db;
global $current_user;

if (empty($current_user->is_admin))
   {
   $tempQuery = "select meetings.id from meetings inner join meetings_cstm on meetings_cstm.id_c=meetings.id inner join users on users.id=meetings.created_by   where meetings.deleted=0 and users.deleted=0 and  meetings.id in( select distinct(meetings.id) from meetings inner join meetings_cstm on meetings_cstm.id_c=meetings.id inner join users on users.id=meetings.created_by inner join meetings_users on users.id=meetings_users.user_id where meetings.deleted=0 and users.deleted=0 and meetings_users.deleted=0 and ( meetings.created_by='" . $current_user->id . "' or meetings.modified_user_id='" . $current_user->id . "' or meetings.assigned_user_id='" . $current_user->id . "' or meetings_users.user_id='" . $current_user->id . "' ))";
   $tempResult = $db->query($tempQuery, false);
   $tempData = array();
   if ($tempResult->num_rows > 0)
      {
      while (($tempRow = $db->fetchByAssoc($tempResult)) != null)
         {
         $tempData[] = $tempRow['id'];
         }
      }

   $tempData = "'" . implode("','", $tempData) . "'";
   if (!empty($this->where))
      {
      $this->where.= " AND meetings.id in (" . $tempData . ")";
      }
     else
      {
      $this->where = " meetings.id in (" . $tempData . ")";
      }
   }
