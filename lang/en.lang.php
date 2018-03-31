<?php
/**
 * 
 * Gigra Refact V3
 * @copyright 2011 (c) stytex.de 
 * @author David Steiman @ stytex.de
 * 
 * 2011: This code was rewritten completely for replacing Empire Space Source 
 * with new Source based on template system an mutlilanguage solution.
 * 
 * 
 * All rights reserved to David Steiman and under the rights of stytex.de
 */

$_LANG = array(
    //Ressources
    "res1" => "Iron",
    "res2" => "Titan",
    "res3" => "Water",
    "res4" => "Hydrogen",
    "energy" => "Energy",
    "energy_all" => "Energy total",
    "energy_left" => "Energie left",
    
    //abk&uuml;ruzngen
    "shortres1" => "I",
    "shortres2" => "T",
    "shortres3" => "H<sub>2</sub>O",
    "shortres4" => "H<sub>2</sub>",
    



    "hour" => "Hour",

    "hours" => "Hours",
    

    "day" => "Day",
    "days" => "Days",
    




    "close" => "Close",
    
    
    "planet" => "planet",
    "moon" => "moon",
    "tf" => "debris field",
    "colony" => "colony",
    
    //Index 
    "username" => "Username",
    "password" => "Password",
	"login" => "Login",
	"round" => "Round",
	"forgotpw" => "Forgot your password?",
	"serverstatus" => "Server status",
	"register" => "Register",
	"this_code" => "This numerical code",
	"in_this_box" => "put into this box",

	//login
	"wrong_username" => "Wrong username",
	"wrong_passwort" => "Wrong password",
	"wrong_code" => "Wrong numerical code",

	//pwvergessen
	"pwmail_subject" => "Gigra - %s - Forgot password",

	"pwmail_text" => "Hello %s,<br />Here again your data for %s.<br />Your username is: %s<br />Your password is: %s<br /><br />You can use this link for login:<br />%slogin.php <br />Have very much fun,<br />your %s Team",
	"pw_thankyou" => "Thank you very much",

	"pw_sent" => "Your password was send to the stored mail adress.",
	"pw_tologin" => "To login",

	"pw_noemail" => "The mail adress could not be found.",
	"pw_forgot" => "I forgot my password",
	"pw_email" => "Your mail adress",

	//Register



	"reg_min_3_chars" => "The username needs a minimum of three characters",
	"reg_max_chars" => "The username can be up to 30 characters long",
	"reg_username_valid" => "The username can contain only lettes, numbers, - and _",
	"reg_user_exists" => "This username is in use",
	"reg_emails_not_match" => "The mail adresses don't match",
	"reg_invalid_email" => "The  e-mail-adress is invalid",
    "reg_mail_exists" => "This E-Mailadress is used",
	"reg_planet_min_2_chars" => "The planetname needs a minimum of 2 characters",


	"reg_planet_max_20_chars" => "The planetname can can be up to 20 characters",
	"reg_planet_valid" => "The planetname can only contain of lettes, numbers, - and _.",
	"reg_accept_terms" => "Please accept the rules and terms of use",



	"reg_max_user" => "The registrationlimit is %s. This universe is full",
	"reg_closed" => "This universe is closed",
	"reg_closed_pw" => "This universe is closed. Enter the secret registrationpassword",
    "reg_closed_starts_on" => "This round starts on %s at %s!",
	"reg_back" => "Back",
	"reg_closed_reg" => "Closed registration",
	"reg_submit" => "OK",
	"regmail_subject" => "Gigra Game- %s - registration",

	"regmail_text" => "Hello %s,<br /> thank you for your registration at %s. Your account is going to be active, as soon as you change your password!<br /><br />Your password is %s<br /><br />You can log in under this link:<br />%s/login.php <br />Have Fun",
	"reg_welcome_gigra" => "Welcome to Gigra!",


	"reg_welcome_text" => "We are pleased to welcome you as a new player.Your password was generated and send to your emailadress.<br>We recommend changing your password directly after your first login.<br>",
    "reg_not_activated" => "You must activate your account.Your account is automatically activated, as soon es you change it under <a href='einst.php'>Settings</a>. For this you need your initial password, which was send to you",

	//navi
	"nav_build_ov" => "Structureoverview",
	"nav_resources" => "Production",
	"nav_galaxy" => "Galaxy",
	"nav_fleet" => "Fleet",
	"nav_highscore" => "Highscore",
	"nav_tech" => "Technology",
	"nav_xp" => "Experience",
	"nav_planets" => "Planets",
	"nav_playerinfo" => "Information",
	"nav_ally" => "Alliance",
	"nav_msg" => "Messages",
	"nav_forum" => "Board",
	"nav_settings" => "Settings",
	"nav_sim" => "Combatsimulator",
    "nav_earn" => "Earning gigrons",
	"nav_logout" => "Logout",
	"nav_new" => "NEW",

	//logout
	"logout" => "LogOut",

	"logout_text" => "Goodbye and see you soon",	
    
    //v3
    "v3_overview" => "Overview",
    "v3_chat" => "Chat",
    "v3_planets" => "Planets",
    "v3_news" => "News!",
    "v3_planetname" => "Planetname",
    "v3_accountname" => "Playername",
    "v3_coords" => "Coordinates",
    "v3_diameter" => "Diameter",
    "v3_temp" => "Temperature", 
    "v3_to" => "to",
    "v3_score" => "Points",
    "v3_rank" => "Rank",
    "v3_of" => "from",
    "v3_level" => "Level",
    "v3_resbonus" => "Productionbonus",
    "v3_buildbonus" => "Buildtimebonus",
    "v3_inactive" => "Bonuspack inactive",
    "v3_forschbonus" => "Researchtimebonus",
    "v3_combatbonus" => "Comabtbonus",
    "v3_build_research" => "Building and Research",
    "v3_buildings" => "Buildings",
    "v3_research" => "Research",
    "v3_ships" => "Ships",
    "v3_defense" => "Defense",
    "v3_rockets" => "Missiles",
    
    
    //news
    
    
    //infobox
    "infoBox_header" => "Building and researching in Gigra III",
    "infoBox_text_h1" => "<h1><b>Building and researching in Gigra</b></h1>",
    "infoBox_text" => "Through a simple click you get information over the obejct(zB.: costs)<br /><br /><span style=\"font-weight: bold;\">Doppelklick:</span><br />Through doubleclick you can immediatly start bulding</b>",
    "infoBox_close" => "close",
    
    
    //v3_bau
    "v3_do_build" => "build",
    "v3_do_build_to" => "build to level %s",
    "v3_do_research" => "Research",
    "v3_do_research_to" => "research to level %s",
    "v3_level_x" => "(Level %s)",
    "v3_need" => "Needs",
    "v3_duration" => "Duration",
    "v3_cancel" => "Cancel",
    "v3_have_x" => "(%s present)",
    "v3_items_all" => "overall",
    "v3_in_product" => "in production",
    


    //Items
	
    // Geb&auml;ude - bulding => item_b
     "item_b1" => "Planetary Citadel",
     "item_b2" => "Researchlabor",
     "item_b19" => "Lunar Base",
     "item_b20" => "Sensor Tower",
     "item_b21" => "Stargateemitter",
    
     "item_b3" => "Ironmine",
     "item_b4" => "Titanmine",
     "item_b5" => "Derrick",
     "item_b6" => "Chemistry Plant",

     "item_b7" => "Advanced Chemistry Plant",
     "item_b8" => "Powerstation",
     "item_b9" => "Ironstorage",
     "item_b10" => "Titanstorage",
     "item_b11" => "Waterstorage",
     "item_b12" => "Hydrogenstorage",
    
     "item_b13" => "Shipyard",
     "item_b14" => "Defense industry",
     "item_b15" => "Shieldstation",
     "item_b16" => "Shieldreactor",
     "item_b17" => "MicroCybot Factory",
     "item_b18" => "Multi Line Core Computer",
    
    // Forschung - research => item_r
     "item_f1" => "Combustiondrive",
     "item_f2" => "Iondrive",
     "item_f3" => "Hyperdrive",
     "item_f4" => "Quantumdrive",
     "item_f5" => "Electron Technology",

     "item_f6" => "Energypooling",
     "item_f7" => "Electricneodymium",
     "item_f8" => "Espionage Technology",

     "item_f9" => "Advanced Armour",
     "item_f10" => "Capacity Technology",
     "item_f14" => "Planetary Management",
     "item_f15" => "Energy Technology",
     "item_f18" => "Mikrotonresearch",
    
    // Schiffe - ships => item_s
     "item_s1" => "Tri Fighter",
     "item_s2" => "Recycler",
     "item_s3" => "Espionagedrone",
     "item_s4" => "Stormfighter",
     "item_s5" => "Raider",
     "item_s6" => "Stealth Fighter",
     "item_s7" => "Colonisationship",
     "item_s8" => "Invasionunit",
     "item_s9" => "C-Force",


     "item_s10" => "Imperial Destroyer",
     "item_s11" => "Imperial Starship",
     "item_s12" => "Small Cargo",

     "item_s13" => "Large Cargo",
     "item_s14" => "Lunar Starstation",
     "item_s15" => "Solar Satellite",
     "item_s16" => "Imperial Transporter",
     "item_s101" => "EMP-Bomber",
     "item_s102" => "War-Drainer",
    
    // Verteidigung - defence => item_d
     "item_v1" => "Rocketlauncher",
     "item_v2" => "Light Lasertower",
     "item_v3" => "Heavy Lasertower",
     "item_v4" => "Elektroncanon",
     "item_v5" => "EMP-Thrower",
     "item_v6" => "Plasmatower",
     "item_v7" => "Nuclear turret",
     "item_v8" => "Mikrotonencanon",

    //Nachrichten
     "msg_drop1_selected"   => "Marked messages",
     "msg_drop1_unselected" => "Non-marked messages",
     "msg_drop1_all"        => "All messages",
     "msg_drop1_system"     => "All systemmessages",
     "msg_drop1_user"       => "All playermessages",
     "msg_drop2_read"       => "Als Gelesen Markieren",
     "mgs_drop2_remove"     => "Delete",
     "mgs_systemname"       => "Systemmessages",
     "mgs_subj"             => "Subject",


     "mgs_nomgs"            => "No messages present",
     "mgs_ans"              => "Answer",
     "msg_nosubj"           => "No Subject",
     "msg_info"             => "Information",

     "msg_send"             => "The message was sent successfully",
     "msg_allmsg"           => "All messages",





     "msg_usermsg"          => "Only playermessages",
     "msg_sysmsg"           => "Only systemmessages",
     "msg_sendefehler"      => "Error at sending message",
     "msg_move_to_archive"  => "move to archive",
     
     
     //Nachrichten Schreiben
     "msgr_from"            => "From:",
     "msgr_to"              => "To:",
     "msgr_subj"            => "Subject:",
     "msgr_msgto"           => "Message to:",
     "msgr_send"            => "Sender",
     
     //neues
     "msg_all_msg" => "All Messages",
     "msg_player" => "Playermessages",
     "msg_combat" => "Combatreports",
     "msg_spy" => "Espionagereports",
     "msg_fleet" => "Fleetreports",
     "msg_build" => "Buildingreports",


     "msg_other" => "Other messages",
     "msg_archive" => "archive",
     
     //Fleet
     "fleet_allship" => "Alle ships",
     "fleet_noship" => "No ships",
     "fleet_sendfleet" => "Send fleet",
     "fleet_to" => "Target",
     "fleet_from" => "Start",
     "fleet_speed" => "Speed",
     "fleet_distance" => "Distance",
     "fleet_duration" => "Duration",
     "fleet_consumption" => "Consumption",
     "fleet_maxspeed" => "Max.Speed.",
     "fleet_capacity" => "Capacity",
     "fleet_mission_options" => "Missions",
     "fleet_cancel" => "Cancel",
     "fleet_send" => "Send Fleet",
     "fleet_hold_time" => "Holding time",

     "fleet_aks_lead_info" => "One of your formed formation can be seen by you and and all members of your alliance and can join it",
     "fleet_found_acs" => "Form formation",
     "fleet_join_acs" => "Join formation",
     
     //fleet notifications
     "fleet_error_no_ship" => "No ships selected",
     "fleet_error_invalid_ships" => "You don't have enough ships",
     "fleet_error_no_missions"  => "No missions available",
     "fleet_error_no_planet" => "This planet doesn't exist",
     "fleet_error_invalid_mission" => "You can't select this mission",
     "fleet_error_invalid_res" => "Incorrect input of ressources",
     "fleet_error_not_enough_res" => "Not enough ressources",
     "fleet_error_not_enough_capa" => "Not enough capacity",
     "fleet_error_not_enough_fuel" => "Not enough fuel",

     "fleet_error_aks_not_in_time" => "Time deviation between your fleet and the leading fleet ist to big",
     "fleet_error_me_in_umod" => "You are in vacation mode",
     "fleet_error_target_in_umod" => "Target is in vacation mode",
     "fleet_success" => "Fleet started",
     "fleet_next" => "Continue",
     "fleet_pls_choose" => "Please choose",
     "fleet_my_planets" => "My planets",
     "fleet_my_targets" => "My targets",
     "fleet_administer_targets" => "Manage targets",
     "fleet_manual" => "Input",
     "fleet_select_target" => "Selection",
     "fleet_save_target" => "Save target",
     
     //events
     "fleet_event_ships" => "Ships",
     "fleet_event_mission" => "Mission",
     "fleet_mission_ag" => "Attack",
     "fleet_mission_ag_p" => "Attack",
     "fleet_mission_aks_lead" => "Formation(Founder)",
     "fleet_mission_aks" => "Formation",
     "fleet_mission_trans" => "Transporting",
     "fleet_mission_stat" => "Stationing",
     "fleet_mission_recy" => "Recycling",
     "fleet_mission_hold" => "Holding",
     "fleet_mission_spio" => "Espionage",
     "fleet_mission_kolo" => "Colonize",
     "fleet_mission_dest" => "Destroy",
     "fleet_mission_inva" => "Invade",
     "fleet_event_arrive" => "Arrival",
     "fleet_event_arrived" => "arrived",
     "fleet_event_back" => "Return",
     "fleet_event_player" => "Player",
     "fleet_event_fleetback" => "Call back",
     
     
     //Galaxie
     "galaxy_galaxy" => "Galaxy",
     "galaxy_planet" => "Planet",
     "galaxy_ally"  => "Alliance",
     "galaxy_rank" => "Rank",
     "galaxy_funcs" => "Function",
     "galaxy_tf" => "Debris Field",
     "galaxy_moon" => "Moon",
     "galaxy_show" => "Show",
     "galaxy_unsetteled" => "Uninhabited planet",
     "galaxy_destructed" => "Destroyed planet",
     
     
     //rohstoffe
     "res_production" => "Production",
     "res_consum" => "Usage",
     "res_factor" => "Productionfactor",
     "res_per_h" => "Hour",
     "res_per_d" => "Day",
     "res_per_w" => "Week",
     "res_capa" => "Storage",
     "res_basic" => "Basic producion",
     "res_h2o_h2" => "Water to Hydrogen",
     "res_energy_bil" => "Energybalance",
     "res_recalc" => "recalculate",
     "res_all_basic_prod" => "Total production",
     "res_active_boost" => "Active productionboost: +%s&percnt; till %s plus %s",

     "res_all_mines_up" => "Start up all mines on all planets",
     
     //technik
     
     "tech_or" => "or",
     "tech_object" => "Object",
     "tech_requirements" => "Requirements",
     "tech_to_build_research" => "To buildings and research",
     "tech_to_military" => "To militaryunits",
     
     //Highscore
     
     "high_player" => "Player",
     "high_level" => "Level",
     "high_planeten" => "Planets",
     "high_planetend" => "Planetaverage",
     "high_flotten" => "Fleets",
     "high_vert" => "Defense",
     "high_forsch" => "Research",
     "high_ally" => "Alliance",
     "high_herrsch" => "Dominance",
     "high_sonnensys" => "Solar system",
     "high_submit" => "Show",
     "high_username" => "Name",
     "high_points" => "Score",
     "high_funktion" => "Functions",
     "high_allyname" => "Alliance Tag",
     "high_allymember" => "Members",
     "high_allydp" => "Points/Member ø",
     "high_level" => "Level",
     "high_infrast" => "Infrastructur",
     "high_kriegsf" => "Warfare",
     "high_besitz" => "Possesion in %",
     "high_coords" => "Coordinates",
     "high_besiedelte" => "Habitated planets",
     "high_punkte_planet" => "Points/Planet Ø",
     "high_in2" => "INACTIVE",
     "high_in1" => "Inactive",
     "high_akt" => "Active",
     "high_rang" => "Rank",
     
     //erfahrung
     
     "exp_to_next_level" => "Needed to next Level",
     "exp_skillpoints" => "Skillpoints",



     "exp_desc" => "Here you see our collected experience in infrastructure, research and warfare. There are experiencepoints, experiencelevel and overall experience.<br>
                    How to gain experience in those categories is explained in their descriptions. Those points are your experiencelevel.<br>
                    In den einzelnen Bereichen k&ouml;nnen Sie Ihre Skillpunkte aufwertenIn those brackets, you can improve your skills and every new level gives you one skillpoint, which can be spent in every talent in this bracket. Please note, that you can't undo your decisions.",
     "exp_infra" => "Infrastructure experience",

     "exp_infra_desc" => "Build buildings to get infrastructureexperience",
     "exp_forsch" => "Research experience",

     "exp_forsch_desc" => "Research technologies to get researchexperience",
     "exp_krieg" => "Warfare experience",

     "exp_krieg_desc" => "Get warexperience through winning battles.",
     
     "exp_skills" => "Skills",
     "exp_infra_bauzeit" => "Coordinate Construction",
     "exp_infra_bauzeit_desc" => "Decreases buildtime by 1%",
     "exp_infra_planeten" => "Imperial Ccoordination",

     "exp_infra_planeten_desc" => "Increases your maximum for planets",
     "exp_infra_rohstoff" => "Geology",

     "exp_infra_rohstoff_desc" => "Increases your ressourceproduction by 1%",
     "exp_krieg_flugzeit" => "Spacephysics",
     "exp_krieg_flugzeit_desc" => "Decreases flighttime by 1%",
     "exp_krieg_treffer" => "Precisionsystems",

     "exp_krieg_treffer_desc" => "Increases your hitchance in battles",
     "exp_forsch_zeit" => "researchefficiency",
     "exp_forsch_zeit_desc" => "Deacreases researchtime by 5%",
     "exp_forsch_geheimschiff1" => "1. secret ship",
     "exp_forsch_geheimschiff1_desc" => "Invest 13 points to get access to the 1. secret ship ", //EMP-Bomber
     "exp_forsch_geheimschiff2" => "2. secret ship",
     "exp_forsch_geheimschiff2_desc" => "Invest 15 points to get access to the 2. secret ",//Wardrainer
     
     
     
     
     //Kampfbericht
     
     "kb_kb" => "Battlereport",

     "kb_intro" => "A fleet reached its destination %s on %s at %s and a battle occured",
     "kb_atter" => "Attacker",
     "kb_deffer" => "Defender",
     "kb_weapons" => "Weapons",
     "kb_shields" => "Shield",
     "kb_hull" => "Hull",
     "kb_type" => "Type",
     "kb_attack" => "Attack",
     "kb_amount" => "Number",
     "kb_destructed" => "destroyed",
     "kb_atter_fleet" => "The attacking fleet",
     "kb_deffer_fleet" => "The defending fleet",

     "kb_shoot_text" => "shoots %s times and hits %s times with an attackpower of %s and %s is absorbed by shields",
     "kb_the_winner_is" => "The winner is",
     "kb_noone" => "nobody",
     "kb_the_atter" => "the attacker",
     "kb_the_deffer" => "the defender",
     "kb_atter_units_lost" => "The attacker has lost %s Units",
     "kb_deffer_units_lost" => "The defender has lost %s Units",


     "kb_tf" => "Now drifting in space: %s iron, %s titan, %s water and %s hydrogen",
     "kb_repaired" => "Following units could be repaired",
     "kb_moonchance" => "chance for moonformation",



     "kb_farmed" => "The attacker gets %s Iron, %s titan, %s water and %s hydrogen",
     "kb_moon" => "The gigantiv masses of the debris field form a moon at the planet",
     "kb_inva" => "The defense has been breached and the attacking imperator takes controll over the planetary citadel and the planet",
     "kb_inva_chance" => "Chance for invasion succeeds",





     "kb_dest" => "The people look up at the sky to see the attacking fleet retreat but suddenly a bright flash illuminates the planet and a microtonbeam destroys the planet",
     "kb_atters_krieg" => "Every attacker gets %s points warexperience",
     "kb_atter_krieg" => "The attacker gets %s points warexperience",
     "kb_deffers_krieg" => "Every defender gets %s points warexperience",
     "kb_deffer_krieg" => "The defender getss %s points warexperience",
     
     //Spielerinfo
     
     "pinfo_info" => "Playerinformation",
     "pinfo_punkte_ges" => "Score(Total)",
     "pinfo_punkte_planet" => "Points(Buildings)",
     "pinfo_punkte_forsch" => "Points(Research)",
     "pinfo_punkte_flotte" => "Points(Fleet)",
     "pinfo_punkte_deff" => "Points(Defense)",
     "pinfo_midTrefferQuoteKampf" => "Minimum hitchance in battle",
     "pinfo_AngriffKampf" => "Attackpower in battle",
     "pinfo_DeffKampf" => "Defensevalue in battle",
     "pinfo_Max_planets" => "Maximum of planets",
     "pinfo_vw" => "Warnings",
     "pinfo_save_stat" => "Save-Status",
     "pinfo_von_5" => " of 5",
     "pinfo_der_res_save" => "of resources saved)",
     
     
     
     //InfoPages
     "info_name" => "Name",
     
     //info schiffe
     "info_ang" => "Attack",
     "info_deff" => "Defense",
     "info_struc" => "Structurepoints",
     "info_capa" => "Storage",
     "info_speed" => "Speed",
     "info_consum" => "Usage",
     "info_engine" => "Drive",
     "info_rapidfire_from" => "has Rapidfire %s from %s",
     "info_rapidfire_against" => "has Rapidfire %s against %s",
     "info_lvl" => "Level",

     "info_sensor" => "Your Sensortower has a range of %s systems",
     
     "einst_acc_settings" => "Accountsettings",
     "einst_username" => "Playername",
     "einst_email" => "E-Mail",
     "einst_old_pw" => "actual password",
     "einst_new_pw" => "new password",
     "einst_pw_repeat" => "confirm password",
     "einst_acc_modes" => "Accountmodi",
     "einst_umod" => "Vacation Mode",
     "einst_acc_delete" => "Delete account",
     "einst_save" => "Save",


     "einst_umod_cant" => "You can't activate vacationmode, because building,research or fleetprocesses are still active.",
     "einst_error_submit_with_pw" => "Confirm these changes with your password.",
     "eins_error_namechange_7_days" => "You can change your playername once in 7 days",
     "eins_error_mailchange_7_days" => "You can change your E-Mailadress only one in 7 days",



     "eins_error_pw_nomatch" => "The passwords don't match.",
     "einst_umod_until" => "Vacation Mode can be deactivated on %s at %s ",
     "eins_success" => "Settings saved!",
     "einst_baumsg" => "Messages for completed buildingprocesses",

     "einst_spioanz" => "Number of espionagedrones for sending through the galaxybutton",
     
     
     //notify
     "notify_acc_delete" => "Your account will be deleted on %s at %s ",

     "notify_umod_until" => "You are in vacation mode til on %s at %s",
     
     //msg
     "msg_construction_complete" => "Construction complete %s (Level %s) ",
     "msg_research_complete" => "Research complete %s (Level %s) ",



     "msg_recycled" => "One of your fleets reached the debris field and excavated:<br>%s Iron, %s Titan, %s Water and %s Hydrogen",
     
     "msg_kb" => "Your fleet reached its destination and a battle occured.<br>".
                    "Lost Units: <font color='%s'>(A: %s, D:%s)</font><br>".
                    "<a href='kb.php?id=%s' target='_blank'>Details</a>",

     "msg_back_with_res" => "One of your fleets returned with following resources:<br>%s Iron, %s Titan, %s Water and %s Hydrogen",
     "msg_back" => "One of your fleets returned.",
     "msg_attacked" => "Your planet has been attacked.<br>".
                        "Lost Units: <font color='%s'>(A: %s, D:%s)</font><br>".
                        "<a href='kb.php?id=%s' target='_blank'>Details</a>",





     "msg_kolo" => "Your fleet reached an uninhabitated planet [%s] . A planetary citadel was build",
     "msg_kolo_fail" => "Your fleet reached its destination [%s] . Colonization was impossible, because the planet was already inhabitated",
     
     "msg_inva" => "Your planet was attacked bei invasionunits.<br>".
                        "Lost Units: <font color='%s'>(A: %s, D:%s)</font><br>".
                        "<a href='kb.php?id=%s' target='_blank'>Details</a>",


     "msg_trans" => "A fleet %s from transported following resources:<br>%s Iron, %s Titan, %s Water and %s Hydrogen",
     "msg_stat" => "A fleet has been stationed on your planet",
     "msg_ally_denied" => "Your application at %s has been declined",


     "msg_ally_accepted" => "You are now a member off %s ",
     "msg_ally_deleted" => "Your alliance has been deleted by %s . You are no longer a member of an alliance",
     "msg_ally_kicked" => "%s has kicked you out of the alliance",



     "msg_dest_win" => "The target could be successfully destroyed ", 
     "msg_dest_fail" => "The tarrget couldn't be destroyed", 
     "msg_dest_epic_fail" => "Through a reaction of the microtonions the lunatic starship explods and destroyed the complete fleet", 
     "msg_dest_chances" => "Chance fpr a planetdestruction: %s &percnt; <br />Chance of a lunatic loss: %s &percnt;",

     "msg_spio" => "A foreign fleet of espionage drones from  %s(%s) has been seen at %s ",
     "msg_gigron_earned" => "You have earned %s Gigron",


     "msg_mikroton" => "After a long and exhausting research of energy, researchers have found a way to activate the microtonmodule but the energy is to massive and the planet got destroyed. But the data could be saved.",
     "msg_board_regitration" => "You have a forumaccount. Logindata l:<br>Username:%s<br>Passowrd:%s<br>E-Mail:%s<br><br>Have fun",
     
     //Spiobericht
     "spio_res" => "Ressources",
     "spio_gebs" => "Buildings",
     "spio_ships" => "Ships",
     "spio_def" => "Defense",
     "spio_research" => "Research",
     "spio_name" => "Name",
     "spio_count_lvl" => "Number/Level",
     "spio_header" => "Espionagereport from %s",
     "spio_chance" => "Chance of espionagedefense: %s &percnt;",
     

     "sensor_no_h2" => "Not enough hydrogen",
     "sensor_no_coords" => "Unknown coordinates",


     "sensor_out_of_range" => "Coordinates are outer reach",
     "sensor_range" => "Your sensortower has a reach of %s systems",
     
     //BonusPacks
     
     "bonus_1" => "Ressourcebooster S",

     "bonus_1_desc" => "Local increase of the production about 5&percnt; for 1 day",
     "bonus_2" => "Ressourcebooster M",

     "bonus_2_desc" => "Local increase of the production about 5&percnt; for 1 week",
     "bonus_3" => "Ressourcebooster L",

     "bonus_3_desc" => "Local increase of the production about 10&percnt; for 1 week",
     "bonus_4" => "Ressourcebooster XL",

     "bonus_4_desc" => "Local increase of the production about 10&percnt; for 1 month",
     
     "bonus_5" => "Cybotaccelerator S",

     "bonus_5_desc" => "Local increase of building speed about 5&percnt; for 1 day",
     "bonus_6" => "Cybotaccelerator M",

     "bonus_6_desc" => "Local increase of building speed about 5&percnt; for 1 week",
     "bonus_7" => "Cybotaccelerator L",

     "bonus_7_desc" => "Local increase of building speed about 10&percnt; for 1 week",
     "bonus_8" => "Cybotaccelerator XL",

     "bonus_8_desc" => "Local increase of building speed about 10&percnt; for 1 month",
     
     "bonus_9" => "Researchaccelerator S",

     "bonus_9_desc" => "Accountwide increase of researchspeed about 5&percnt; for 1 day",
     "bonus_10" => "Researchaccelerator M",

     "bonus_10_desc" => "Accountwide increase of researchspeed about 5&percnt; for 1 week",
     "bonus_11" => "Researchaccelerator L",

     "bonus_11_desc" => "Accountwide increase of researchspeed about 10&percnt; for 1 week",
	 "bonus_12" => "Researchaccelerator XL",

     "bonus_12_desc" => "Accountwide increase of researchspeed about 10&percnt; for 1 month",
     
     "bonus_13" => "Combatbooster S",

     "bonus_13_desc" => "Accountwide increase of all combatrelevant technologies and hitchance about 5&percnt; for 1 day",
     "bonus_14" => "Combatbooster M",

     "bonus_14_desc" => "Accountwide increase of all combatrelevant technologies and hitchance about 55&percnt; for 1 week",
     "bonus_15" => "Combatbooster L",

     "bonus_15_desc" => "Accountwide increase of all combatrelevant technologies and hitchance about 10&percnt; for 1 week",
     "bonus_16" => "Combatbooster XL",

     "bonus_16_desc" => "Accountwide increase of all combatrelevant technologies and hitchance about 10&percnt; for 1 month",
     
     "bonus_packs" => "BonusPacks",
     "bonus_activate" => "Activate now",

     "bonus_warning" => "Warning: This bonus will be activated on %s . If you want the bonus on another planet switch to it.",
     "bonus_buy" => "Earn bonus for %s Gigron erwerben",
     "bonus_not_enough_gigrons" => "Not enough Gigron",
     "bonus_item_not_avaible" => "You don't have this pack:",
     "bonus_success" => "the bonus has been activated !",
     "bonus_buyed" => "The bonus has been buyd",
     
     //dbError
     "db_error" => "Error",

     "db_error_text" => "An error occured! Please try again",
     
     //Fragen
     "question_title" => "Question",
     "question_yes" => "Yes",
     "question_no" => "No",
     



     "question_1" => "Do you really want to fulfill this action?",
     "question_2" => "Are you sue you want to leave your alliance?",
     "question_mikroton" => "If you start the research your plaent will be destroyed.",
     
     //asperre

     "asperre" => "Currently a combatblockade has been activated from %s to %s !",
     
     //plansettings
     "planset_rename" => "Rename",
     "planset_leave" => "Abandon",
     "planset_makeHP" => "Mainplanet",
     
     
     //Status
     "status" => "Status",
     "status_uni" => "Universe",
     "status_galaxies" => "Galaxy",
     "status_systems" => "System/Galaxy",
     "status_buildspeed" => "Buildingspeed",
     "status_resspeed" => "Ressourceincrease",
     "status_fleetspeed" => "Fleetspeed",
     "status_noobspeed" => "Noobspeed",
     "status_maxuser" => "Max. Player",
     "status_usercount" => "Player",
     "status_active" => "Active",
     "status_passive" => "Passive",
     "status_inactive" => "Inactive",
     "status_online" => "Online",
     "status_planets" => "Planets",
     "status_leaved" => "inhabitated",
     "status_resources" => "Ressources in circuit",
     
     "ksim_ships" => "Ships",
     "ksim_properties" => "Playerdata",
     "ksim_property" => "Properties",
     "ksim_result" => "Result",
     "ksim_winner" => "Winner",
     "ksim_alost" => "Loss attacker",
     "ksim_vlost" => "Loss Defender",
     "ksim_winnings" => "Loot",
     "ksim_mondchance" => "Chance for a moon",
     "ksim_actions" => "actions",
     "ksim_calculate_battle" => "calculate battle",
     
     
     //verdienen
     "earn_gigrons" => "Earn Gigron!",
     "earn_1st" => "1. through play",

     "earn_1st_desc" => "Earn Gigron through attacking and destroying units. 100 units are 1 Gigron.",
     "earn_2nd" => "2. Advertise to players!",

     "earn_2nd_desc" => "Player who register through this and gain points and are active fpr 7 days will earn you 150k Gigron. If he gets 100k points you can earn another 100k Gigron",
     "earn_your_url" => "Your advertisemient-URL",
     "earn_active" => "Permament active",
     "earn_no_player" => "No advertised players",
     "earn_status_0" => "Waiting for 7 days active playtime",



     "earn_status_1" => "Player active, earn 150k Grigron now!",
     "earn_status_2" => "Waiting on leaving the noobprotection",
     "earn_status_3" => "Player's no more a noob, earn %s Gigron!",
     "earn_status_4" => "Thanks for a player!",
     "earn_3rd" => "3. Our adviertisment-banner",

     "earn_3rd_desc" => "Bind this banner into your site or blog and earn 500 Gigron per click.",
     "earn_4th" => "4. Vote!",

     "earn_4th_desc" => "Vote for Gigra_game.de to get 1k Gigron per votebutton.",
     
     //tutorial
     "tut_exit" => "End tutorial",
     "tutorial" => "Tutorial",
     "tut_start" => "Start tutorial",

     "tut_no_tuts" => "No tutorial for this view",
     
     //ItemTexte
     














     "itemtext_b1" => "The planetry citadel is the center of your empire and coordinates buildingsproceeses on your planets.",
     "itemtext_b2" => "Increasing the level of the researchcenter decreases researchtime.",
     "itemtext_b3" => "Iron is an important part of buildings, ships and defenses.",
     "itemtext_b4" => "Titan is important for shields and microtechnology and also für buildings.",
     "itemtext_b5" => "Water is important for chemical processes and for research.",
     "itemtext_b6" => "The chemiefabrics produces hydrogen through electrolysis, but is very inefficient (1:5).Hydrogen is used for fuel and research",
     "itemtext_b7" => "The advanced chemiefabric produces more hydrogen per water (1:2) then the chemiefabric",
     "itemtext_b8" => "Increasing the powerstaionlevel will increase the amount of energy you have for your mines and fabrics.If you have not enough energy, ressourceproduction will be decreased",
     "itemtext_b9" => "Increasing the level of the storage gives you a higher storagevolumen. If the storgae is filled, further iron will be put in a debris field around your planet.",
     "itemtext_b10" => "Increasing the level of the storage gives you a higher storagevolumen. If the storgae is filled, further titan will be put in a debris field around your planet.",
     "itemtext_b11" => "Increasing the level of the storage gives you a higher storagevolumen. If the storgae is filled, further water will be put in a debris field around your planet.",
     "itemtext_b12" => "Increasing the level of the storage gives you a higher storagevolumen. If the storgae is filled, further hydrogen will be put in a debris field around your planet.",
     "itemtext_b13" => "The shipyard is for producing ships. Increasing the level will speed up construction and gives you access to more complex ships",
     "itemtext_b14" => "Defenses are build here. Increasing the level will decrease buildingtime and you get access to more developed defenses.",
     
     





     "itemtext_b17" => "The microcybotfabric decreases the buildingtime bei 50% for ships,defenses and buildings per level",
     "itemtext_b18" => "This buildings lets you build buildings simultaneously. The higher the level the more buildings can be build at the same time.",
     "itemtext_b19" => "The moonbase lets you build buildings on the moon and drecreases buildingtime.",
     "itemtext_b20" => "The sensortower lets you monitor fleetmovements on planets in reach. Every use cocts 10.000 hydrogen and the reach is defined by <sup>3</sup> - 1",
     "itemtext_b21" => "Stargates send mass throughout the galaxy to other stargates they have access to. You can sned fleets without timeloss from one moon to another you own.Every use costs 10 Million hydrogen.",
     













     "itemtext_f1" => "The simple technology for combustion drives is still in use in scintific time-Increasing the level increases the speed of the earliest ships.",
     "itemtext_f2" => "The iondrive works similar to the combustiondrive, but is more advanced and is used in most midle-class ships. Increasing the level increaes the speed.",
     "itemtext_f3" => "The hperdrive is uses spacewarp technology to move the ships through spaced and ist used in all high class ships",
     "itemtext_f4" => "The quantumdrive is the most advanced technology and is used only in to ships.",
     "itemtext_f5" => "Electrotech increases the weaponefficiency by 10% per level through optimized energypools",
     "itemtext_f6" => "Energypooling increases the efficiency of shields through pooling the energy at impactspots by 10% per level.",
     "itemtext_f7" => "Through optimized workingprocesses ressourceextraction. The higher the level the higher the increase of the extraction.",
     "itemtext_f8" => "The espionagetechnology is a important part of modern warfare. Every level increases the function of the espionagedrones.",
     "itemtext_f9" => "This research increases the hull of all ships by 10% per level.",
     "itemtext_f10" => "Through storagereorganisation the capacity of ships is increased by 5% per level",
     "itemtext_f14" => "Only diplomacy and logistic can give you the control over your empire. Every level increases your maximum of planets by 2.",
     "itemtext_f15" => "The safe and controllod handling of enegry is the A and O and every levelincrease increases the energyproduction.",
     "itemtext_f18" => "The ultimate answer to everything.",
     



























     "itemtext_s1" => "The Tri_fighter is a small class battleship, which is a backbone of every fleet to protect the stronger ships and stop enemy fire. It is also evry usefull against anti-ship fleets, especially the c-force.",
     "itemtext_s2" => "The recycler is unique in its shielddesign. It is the only ship that can navigate saveliy through debris fields and collect them.",
     "itemtext_s3" => "The fastest and smallest ship in the universe. It's sole purpose is to gather information about planet and players.",
     "itemtext_s4" => "This ship is a stronger variant of the Tri-fighter and is mostly used against Imperial destroyers and Raiders.",
     "itemtext_s5" => "One hell of a ship against small craft like the Trifighter. It's the only effective ship against the masses of Tri-fighters, that are present in the biggest fleets.",
     "itemtext_s6" => "The stealthcorvette is a high class secret ship for stealthattacks on planets with high activity.",
     "itemtext_s7" => "This ships purpose is to terraform other planets and to increase the size of your empire. It should always be escrotet to garanty it's safety.",
     "itemtext_s8" => "This ship is manned with groundforces to destabalicize the infrastructure on planets and to gain controll of them. The more invasionunits are used, the higher the chance for a successfull invasion.",
     "itemtext_s9" => "The C-force is a antiship-ship. It is highly effective against all smaller spacecrafts except the Tri-fighter. In combination with the Raider and the Trifighter, enemy fleets gonna have a bad time.",
     "itemtext_s10" => "The Imperial destroyer is an anti-defense ship, that should always be paired up witgh Tri-forces. Its only weakness is the stormfighter.",
     "itemtext_s11" => "The Imperial Stardestroyer is a kings-class battleship suited for destroying heavy ships and defense.",
     "itemtext_s12" => "The small tradingvessel has a fast engine suited for its purpose and fast attacks.",
     "itemtext_s13" => "The large tradingvessel is a medium sized and medium fast transportship.Not as fast as the small version but faster than the imperial version.",
     "itemtext_s14" => "Many thousand years before the third gigranian era, a dark lord designed the blueprint for the ultimate weapon of this universe, the lunatic starship. It is incredibly powerful and can destroy fleets, planets and moons in a wimp.",
     "itemtext_s15" => "Through simplest technologie solar satellites are a cheap but destructible energysource.",
     "itemtext_s16" => "This ships are used by the great tradingfederations during the third gigranian era. They have a very big cargo and a medium speed, but are still faster than all battleships and can be used for all raids.",
     "itemtext_s101" => "Through secret research, scientist developed a new form of battleship. The EMP-Bomber can weaken enemy ships by decreasing their attackpotencial. It is the 1. secret ship",
     "itemtext_s102" => "The War-Drainer is a fortification of the EMP-Bomber and can develope electromagnetic shields , which decrease the powerlevel of enemy ships and absorb this level to its own firepower to increase its attackpower. It is the 2. secret ship",
  
    "item_b1_shortdesc" => "Koordiniert den Bau von Geb&auml;uden",
    "item_b2_shortdesc" => "Erm&ouml;glicht das Erforschen neuer Technologien",
    "item_b19_shortdesc" => "Erm&ouml;glicht das Besiedeln eines Mondes",
    "item_b20_shortdesc" => "Ermittelt Flottenbewegungen im Umkreis von Stufe^3 - 1",
    "item_b21_shortdesc" => "Erm&ouml;glicht das Erzeugen von Sternentore durch den Sensorturm",
    "item_b3_shortdesc" => "F&ouml;rderung von Eisen",
    "item_b4_shortdesc" => "F&ouml;rderung von Titan",
    "item_b5_shortdesc" => "F&ouml;rdert Wasser",
    "item_b6_shortdesc" => "Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 5:1)",
    "item_b7_shortdesc" => "Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 2:1)",
    "item_b8_shortdesc" => "Erzeugt Energie",
    "item_b9_shortdesc" => "Zur Lagerung von Eisen",
    "item_b10_shortdesc" => "Zur Lagerung von Titan",
    "item_b11_shortdesc" => "Zur Lagerung von Wasser",
    "item_b12_shortdesc" => "Zur Lagerung von Wasserstoff",
    "item_b13_shortdesc" => "Zum Bau von Schiffen",
    "item_b14_shortdesc" => "Die Verteidigungsanlage ihres Planeten. Kann mit Verteidigungst&uuml;rmen best&uuml;ckt werden",
    "item_b15_shortdesc" => "Erh&ouml;ht den Verteidigungswert Ihrers Planeten",
    "item_b16_shortdesc" => "Energieversorgung f&uuml;r planetares Schild",
    "item_b17_shortdesc" => "Verk&uuml;rzt die Bau- und Produktionszeiten pro Stufe um 50 Prozent",
    "item_b18_shortdesc" => "Erweitert die Anzahl der parallel baubaren Geb&auml;ude pro Stufe um 1",
    "item_f1_shortdesc" => "Antriebstechnik f&uuml;r kleine Raumschiffe",
    "item_f2_shortdesc" => "Sehr schnelle Antriebstechnik",
    "item_f3_shortdesc" => "Sparsamer und schneller Antrieb, f&uuml;r gro&szlig;e Raumschiffe",
    "item_f4_shortdesc" => "Modernste Antriebsform der Galaxie",
    "item_f5_shortdesc" => "Ionenwaffen erforschen",
    "item_f6_shortdesc" => "Erh&ouml;ht die Effizienz von Ionenwaffen",
    "item_f7_shortdesc" => "Waffenst&auml;rke durch magnetisierte plasmaartige Neodymmolek&uuml;le",
    "item_f8_shortdesc" => "Verbessert die Funktion von Spionagesonden",
    "item_f9_shortdesc" => "Erh&ouml;ht die Panzerung aller Raumschiffe",
    "item_f10_shortdesc" => "Erh&ouml;ht die Ladekapazit&auml;t aller Raumschiffe",
    "item_f14_shortdesc" => "Erweitert pro Stufe die maximale Planetenanzahl um 2",
    "item_f15_shortdesc" => "Wird Ben&ouml;tigt f&uuml;r alle Energietechiken",
    "item_f18_shortdesc" => "Extrem starke Waffe",
    





    "item_s1_shortdesc" => "Weakest and cheapest battleship.",
    "item_s2_shortdesc" => "Gathers debris fields after battles",
    "item_s3_shortdesc" => "Gathers information of other planets ",
    "item_s4_shortdesc" => "Stronger than the Tri-Fighter and effective against imperial destroyers",
    "item_s5_shortdesc" => "The raider is a fast and efficient ship, when it comes to Tri-Fighter",
    "item_s6_shortdesc" => "Gets detect only short before the battle",


    "item_s7_shortdesc" => "For inhabitating other planets",
    "item_s8_shortdesc" => "For taking over other planets",
    "item_s9_shortdesc" => "Fast and effective ship against other ships",





    "item_s10_shortdesc" => "Very strong against defense",
    "item_s11_shortdesc" => "Strongest warship",
    "item_s12_shortdesc" => "Small cargoship",
    "item_s13_shortdesc" => "Improvement of the small cargoship.",
    "item_s14_shortdesc" => "Ultimate battlestation",
    "item_s15_shortdesc" => "Produces energy",


    "item_s101_shortdesc" => "Weakens the enemy",
    "item_s102_shortdesc" => "Weakens the enemy and boosts itself",
    
    
    
    "item_v1_shortdesc" => "Cheapest defense",
    "item_v2_shortdesc" => "Small defensetower",
    "item_v3_shortdesc" => "Improved defensetower",
    "item_v4_shortdesc" => "New imrpoved defense",
    "item_v5_shortdesc" => "Sends EMP-waves",
    "item_v6_shortdesc" => "Very efficient and short buildingtime",
    "item_v7_shortdesc" => "Really strong defense",
    "item_v8_shortdesc" => "Strongest defense",

  
     //Ally
     "ally_page_allianz" => "Alliance",
     "ally_page_adminmenu" => "Administrationmenu;",
     "ally_page_bewerbungen" => "Applications",
     "ally_page_allyliste" => "Alliancememberr",
     "ally_page_forum" => "Allianceforum",
     "ally_page_allymsg" => "Write alliancemessage",
     "ally_page_adminrundmail" => "Write gamemail (only Gameadmins)",
     "ally_page_verlassen" => "Leave alliance",
     "ally_verwalten_title" => "Administrate alliance",
     "ally_verwalten_bewerbung_abg" => "Application declined!",
     "ally_verwalten_bewerbung_ang" => "Application accepted!",
     "ally_verwalten_annehmen" => "Accept!",
     "ally_verwalten_ablehnen" => "Decline!",
     "ally_verwalten_keine_bew" => "No new application!",
     "ally_verwalten_upload_fehler" => "Upload mistake!",
     "ally_verwalten_rechte" => "Administrate rights",
     "ally_verwalten_save" => "Save",
     "ally_verwalten_member_verw" => "Administrate Member",
     "ally_verwalten_username" => "Username",
     "ally_verwalten_rang" => "Rank",

     "ally_verwalten_recht_memberlist" => "See Memberlist",
     "ally_verwalten_recht_rundmail" => "Send circ. message",
     "ally_verwalten_recht_verwalten" => "Manage",
     "ally_verwalten_recht_entf" => "Delete",
     "ally_verwalten_neuer_rang" => "Create new privileges",
     "ally_std_rang_founder" => "Founder",
     "ally_std_rang_neu" => "Newcommer",
     "ally_verwalten_abtreten" => "Pass alliance",








     "ally_verwalten_punkte" => "Points",
     "ally_verwalten_status" => "Status",
     "ally_verwalten_offline" => "Offline",
     "ally_verwalten_umod" => "In vacationmode",
     "ally_verwalten_rechte_save" => "Save rights",
     "ally_verwalten_logo_page" => "Alliance-Logo & Homepage",

     "ally_verwalten_hinweistext" => "Pictures and homepages with pornografic and/or illigal content are strictly forbidden !",
     "ally_verwalten_bild_alt" => "Alliance Logo",
     "ally_verwalten_kein_bild" => "No logo present!",
     "ally_verwalten_homepage" => "Homepage:",
     "ally_verwalten_beschreibung" => "Alliancetext/Description (BBCode)",
     "ally_verwalten_ally_del" => "Delete alliance",
     "ally_verwalten_ally_del_text1" => "Do you really want to delte the alliance?",
     "ally_verwalten_passwort" => "Your password:",
     "ally_verwalten_ally_del_submit" => "Delte alliance",
     "ally_gruenden_title" => "found alliance",
     "ally_gruenden_tag" => "Alliancetag (max. 8 signs)",
     "ally_gruenden_name" => "Alliance name (max. 35 signs)",
     "ally_gruenden_submit" => "Found alliance",
     "ally_gruenden_bestaetigung" => "Alliance has been found",
     "ally_gruenden_allyseite" => "to the alliancepage",
     "ally_gruenden_empty" => "Panel not filled",

     "ally_gruenden_exists" => "A alliance with the same name/tag already exists!",
     "ally_gruenden_tag1" => "Tag invalid",
     "ally_gruenden_name1" => "Name invalid",
     "ally_member_name" => "Name",
     "ally_member_rang" => "Rank",
     "ally_member_punkte" => "Points",
     "ally_member_status" => "Status",
     "ally_member_off" => "Offline",
     "ally_member_umod" => "In vacationmode",
     "ally_rundmail_title" => "Alliance",
     "ally_rundmail_gesendet" => "Alliancemail send",
     "ally_rundmail_zur_ally" => "back to the alliancepage",
     "ally_suche_title" => "alliancesearch",
     "ally_suche_gruenden" => "Found alliance",
     "ally_suche_tag" => "Day",
     "ally_suche_name" => "Name",
     "ally_suche_func" => "Function",
     "ally_suche_bewerben" => "Apply",
     "ally_suche_keine_allys" => "No alliance found",
     "ally_suche_suchen" => "Search",
     "ally_circ_mail" => "Alliancemail",
     
     "player" => "Player",
     "planet_count" => "Number of planets",
     
     //Pranger

     "verwarnung_konseq_1" => "No consequences until next warning",
     "verwarnung_headtext_1" => "1 warning",

     "verwarnung_konseq_2" => "Your account is frozen until %s .Your planet can be attacked",
     "verwarnung_headtext_2" => "Your account is frozen",
     "verwarnung_konseq_3" => "You have been banned from the chat until %s .",
     "verwarnung_headtext_3" => "Your ccount is frozen",

     "verwarnung_konseq_4" => "Your account is frozen until %s . Your planet cannot be attacked(Vacationmode)",
     "verwarnung_headtext_4" => "Your account is frozen",

     "verwarnung_konseq_5" => "Your account has been banned from the game and is going to be deleted.",
     "verwarnung_headtext_5" => "Your account is banned",

     "verwarnung_info1" => "Your account has been warn on %s from %s with following reason:",
     "verwarnung_info2" => "The consequences are:",
     
     //Suche
     "search" => "Search",
     "search_for_player" => "for players",
     "search_for_allys" => "for alliances",
     "search_no_results" => "No results",
     
     //Imperium
     "empire" => "empire",
     "empire_planet_info" => "Planetinfo",
     
     //PowerCollect
     

     "powercol" => "Ressourcecentralisation",
     "powercol_resprio" => "Ressourcepriority",

     "powercol_resprio_desc" => "Click on a resource in the order you want them to be prioriticized(If you click on iron first, it will be loaded first)",
     "powercol_selship" => "Transportships",

     "powercol_selship_desc" => "Click on the ships which should do the transport",
     "powercol_reset" => "Reset",
     "powercol_start" => "Start resourcecentralisation !",

     "powercol_list" =>  "List resourcecentralisation (not starting)",
     "powercol_send_from" => "send of %s",
    


     "e500" => "Internal server-error",
     "e500_desc" => "Please try again.",
     "e500_reload" => "Refresh site",
     
     "anti_spam" => "Spamsicherheitssperre, kontaktieren Sie einen Admin!",
     


















     "attack_on" => "You are under attack %s !",
     "attack_and" => "and",
     "system_offline" => "The sstem is currently offline, please be patiendDas System ist derzeit offline, bitte haben Sie Geduld!",
     
     "hkurse" => "current price",   
     
     
     
     "kb_publish" => "publish report",
     "kb_main_comment" => "main comment",
     "kb_write_comment" => "write comment",
     "kb_title" => "Title",
     
     "hof_title" => "Hall Of Fame",
     "hof_units" => "Units",
     "hof_kb" => "CR",
     "hof_date" => "Date",
     "hof_last_kb" => "Current battles",
     "hof_notice" => "published combat report appears after 2 hours in the HoF",
);

?>