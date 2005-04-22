# Connection: Staging (new)
# Host: 195.81.39.239
# Saved: 2005-03-16 16:58:45
# 
 SELECT
  np_loc.obj_id as loc_obj_id,   # Local Net_Port Prim Key
  np_loc.nr as loc_nr,       # Local Net_Port Prim Key
  np_loc.name as loc_name,     # Local port name
  npd_loc.npd_id as loc_npd_id,
  npd_rem.npd_id as rem_npd_id,
  npd_loc.np_id_from, # Local Net_Port_Definition Primary key
  npd_loc.np_id_to,   # Remote Net_Port_Definition Primary key
  npd_rem.obj_id as rem_obj_id,     # Remote Object id
  npd_rem.np_id_from, # Remote Net_Port_Definition Primary key
  npd_rem.np_id_to,   # Local Net_Port_Definition Primary key
  npd_loc.comment as loc_comment,    # Local port comment
  np_rem.name as rem_name,        # Remote port name
  np_rem.nr as rem_nr,        # Remote port nr
  obj_rem.code as rem_code
 FROM
  Net_Port np_loc
 LEFT JOIN Net_Port_Definition npd_loc ON npd_loc.obj_id = np_loc.obj_id AND np_loc.nr = npd_loc.np_id_from
 LEFT JOIN Net_Port_Definition npd_rem ON npd_rem.npd_id = npd_loc.np_id_to
 LEFT JOIN Net_Port np_rem ON np_rem.obj_id = npd_rem.obj_id AND np_rem.nr = npd_rem.np_id_from
 LEFT JOIN Object obj_rem ON obj_rem.obj_id = npd_rem.obj_id
 WHERE 
  np_loc.obj_id = '461' AND
  np_loc.nr = '9' 

SELECT
  npd_loc.npd_id     as loc_npd_id,
  npd_loc.obj_id     as loc_np_obj_id,
  npd_loc.np_id_from as loc_np_nr,
  npd_rem.npd_id     as rem_npd_id,
  npd_rem.obj_id     as rem_np_obj_id,
  npd_rem.np_id_from as rem_np_nr
from Net_Port_Definition npd_loc 
left join Net_Port_Definition npd_rem on npd_rem.npd_id = npd_loc.np_id_to
where npd_loc.obj_id=461 and npd_loc.np_id_from=1




SELECT npd_loc.npd_id AS loc_npd_id, npd_loc.obj_id AS loc_np_obj_id, npd_loc.np_id_from AS loc_np_nr, npd_rem.npd_id AS rem_npd_id, npd_rem.obj_id AS rem_np_obj_id, npd_rem.np_id_from AS rem_np_nr FROM Net_Port_Definition npd_loc LEFT JOIN Net_Port_Definition npd_rem ON npd_rem.npd_id = npd_loc.np_id_to WHERE npd_loc.obj_id='467' AND npd_loc.np_id_from='1'

