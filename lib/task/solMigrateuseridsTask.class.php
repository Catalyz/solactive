<?php

class solMigrateuseridsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'sol';
    $this->name             = 'migrate-user-ids';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [sol:migrate-user-ids|INFO] task does things.
Call it with:

  [php symfony sol:migrate-user-ids|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
//    $databaseManager = new sfDatabaseManager($this->configuration);
//    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    //region mapOld
  	$mapOld['0682266819'] = 348;
  	$mapOld['0561342521'] = 349;
  	$mapOld['0650488553'] = 350;
  	$mapOld['0561122093'] = 351;
  	$mapOld['0562161572'] = 352;
  	$mapOld['0534465088'] = 353;
  	$mapOld['0681382438'] = 354;
  	$mapOld['0645563664'] = 355;
  	$mapOld['0612071239'] = 356;
  	$mapOld['0628045840'] = 357;
  	$mapOld['0626190778'] = 358;
  	$mapOld['0633338693'] = 359;
  	$mapOld['0534429825'] = 360;
  	$mapOld['0534429825'] = 361;
  	$mapOld['0665612637'] = 362;
  	$mapOld['0534773573'] = 363;
  	$mapOld['0662646692'] = 364;
  	$mapOld['0621021641'] = 365;
  	$mapOld['0612568956'] = 366;
  	$mapOld['0561751676'] = 367;
  	$mapOld['0581341608'] = 368;
  	$mapOld['0606818231'] = 369;
  	$mapOld['0562113403'] = 370;
  	//$mapOld['0650488553'] = 371;
  	$mapOld['0679074691'] = 372;
  	$mapOld['0687277479'] = 373;
  	$mapOld['0687077290'] = 374;
  	$mapOld['0660456280'] = 375;
  	$mapOld['0561133201'] = 376;
  	$mapOld['0607053689'] = 377;
  	$mapOld['0561439780'] = 378;
  	$mapOld['0561125801'] = 379;
  	$mapOld['0561228382'] = 380;
  	$mapOld['0623067818'] = 381;
  	$mapOld['0567522701'] = 382;
  	$mapOld['0681017860'] = 383;
  	$mapOld['0623679657'] = 384;
  	$mapOld['0615687137'] = 385;
  	$mapOld['0621200866'] = 386;
  	$mapOld['0142688580'] = 387;
  	$mapOld['0623491810'] = 388;
  	$mapOld['0534313311'] = 389;
  	$mapOld['0630337767'] = 390;
  	$mapOld['0562880713'] = 391;
  	$mapOld['0618892117'] = 392;
  	$mapOld['0561750241'] = 393;
  	$mapOld['0561342534'] = 394;
  	$mapOld['0562736680'] = 395;
  	$mapOld['0561216912'] = 396;
  	//$mapOld['0561216912'] = 397;
  	$mapOld['0660261372'] = 398;
  	$mapOld['0683765831'] = 399;
  	$mapOld['0681528829'] = 400;
  	$mapOld['0675209255'] = 401;
  	$mapOld['0561429507'] = 402;
  	$mapOld['0561539563'] = 403;
  	$mapOld['0562741740'] = 404;
  	$mapOld['0561445702'] = 405;
  	$mapOld['0561211747'] = 406;
  	$mapOld['0562579025'] = 407;
  	$mapOld['0534429251'] = 408;
  	$mapOld['0562146485'] = 409;
  	$mapOld['0561524510'] = 410;
  	$mapOld['0686365744'] = 411;
  	$mapOld['0561252297'] = 412;
  	$mapOld['0534406472'] = 413;
  	$mapOld['0626453278'] = 414;
  	$mapOld['0531616309'] = 415;
  	$mapOld['0562576340'] = 416;
  	$mapOld['0665682150'] = 417;
  	$mapOld['0562141502'] = 418;
  	$mapOld['0665027511'] = 419;
  	$mapOld['0561438010'] = 420;
  	$mapOld['0562275048'] = 421;
  	$mapOld['0563272512'] = 422;
  	$mapOld['0954620401'] = 423;
  	$mapOld['0561442603'] = 424;
  	$mapOld['0562141255'] = 425;
  	$mapOld['0645534184'] = 426;
//endregion
  	//region mapNew
  	$mapNew=array() ;
  	$mapNew['0682266819'] = 2;
  	$mapNew['0645534184'] = 105;
  	$mapNew['0561342521'] = 107;
  	$mapNew['0650488553'] = 108;
  	$mapNew['0561122093'] = 122;
  	$mapNew['0562161572'] = 139;
  	$mapNew['0534465088'] = 140;
  	$mapNew['0681382438'] = 141;
  	$mapNew['0645563664'] = 142;
  	$mapNew['0612071239'] = 143;
  	$mapNew['0628045840'] = 144;
  	$mapNew['0626190778'] = 145;
  	$mapNew['0633338693'] = 146;
  	$mapNew['0534429825'] = 147;
  	$mapNew['0534429825'] = 148;
  	$mapNew['0665612637'] = 149;
  	$mapNew['0534773573'] = 150;
  	$mapNew['0662646692'] = 151;
  	$mapNew['0621021641'] = 152;
  	$mapNew['0612568956'] = 153;
  	$mapNew['0561751676'] = 154;
  	$mapNew['0581341608'] = 155;
  	$mapNew['0606818231'] = 156;
  	$mapNew['0562113403'] = 157;
  	$mapNew['0650488553'] = 158;
  	$mapNew['0679074691'] = 161;
  	$mapNew['0687277479'] = 162;
  	$mapNew['0687077290'] = 163;
  	$mapNew['0660456280'] = 164;
  	$mapNew['0561133201'] = 202;
  	$mapNew['0607053689'] = 203;
  	$mapNew['0561439780'] = 204;
  	$mapNew['0561125801'] = 205;
  	$mapNew['0561228382'] = 206;
  	$mapNew['0623067818'] = 207;
  	$mapNew['0567522701'] = 208;
  	$mapNew['0681017860'] = 209;
  	$mapNew['0623679657'] = 210;
  	$mapNew['0615687137'] = 211;
  	$mapNew['0621200866'] = 212;
  	$mapNew['0142688580'] = 213;
  	$mapNew['0623491810'] = 216;
  	$mapNew['0534313311'] = 217;
  	$mapNew['0630337767'] = 218;
  	$mapNew['0562880713'] = 219;
  	$mapNew['0618892117'] = 220;
  	$mapNew['0561750241'] = 221;
  	$mapNew['0561342534'] = 222;
  	$mapNew['0562736680'] = 237;
  	$mapNew['0561216912'] = 238;
  	$mapNew['0561216912'] = 239;
  	$mapNew['0660261372'] = 241;
  	$mapNew['0683765831'] = 243;
  	$mapNew['0681528829'] = 244;
  	$mapNew['0681528829'] = 245;
  	$mapNew['0675209255'] = 246;
  	$mapNew['0561429507'] = 247;
  	$mapNew['0561539563'] = 248;
  	$mapNew['0562741740'] = 249;
  	$mapNew['0561445702'] = 250;
  	$mapNew['0561211747'] = 251;
  	$mapNew['0562579025'] = 252;
  	$mapNew['0534429251'] = 253;
  	$mapNew['0562146485'] = 254;
  	$mapNew['0561524510'] = 255;
  	$mapNew['0686365744'] = 256;
  	$mapNew['0561252297'] = 257;
  	$mapNew['0534406472'] = 258;
  	$mapNew['0626453278'] = 259;
  	$mapNew['0531616309'] = 260;
  	$mapNew['0562576340'] = 261;
  	$mapNew['0665682150'] = 262;
  	$mapNew['0562141502'] = 263;
  	$mapNew['0665027511'] = 264;
  	$mapNew['0561438010'] = 265;
  	$mapNew['0562275048'] = 266;
  	$mapNew['0563272512'] = 267;
  	$mapNew['0954620401'] = 268;
  	$mapNew['0561442603'] = 269;
  	$mapNew['0562141255'] = 271;
  	$mapNew['0562276289'] = 272;
  	$mapNew['0561583256'] = 273;
  	$mapNew['0637459712'] = 274;
  	$mapNew['0619694086'] = 275;
  	$mapNew['0679285304'] = 276;
  	$mapNew['0647494144'] = 277;
  	$mapNew['0658765487'] = 278;
  	$mapNew['0561330630'] = 279;
  	$mapNew['0662571373'] = 280;
  	$mapNew['0670596650'] = 281;
  	$mapNew['0636741448'] = 282;
  	$mapNew['0534314882'] = 283;
  	$mapNew['0688376914'] = 284;
  	$mapNew['0951356296'] = 285;
  	//endregion

  	$delta = 1000;

  	$this->log(sprintf('UPDATE ticket_tracking SET operator_id = operator_id + %d, actor_id = actor_id + %d;', $delta, $delta));
  	foreach($mapOld as $phone => $oldId){
  		$newId = $mapNew[$phone];
  		$this->log(sprintf('UPDATE ticket_tracking SET operator_id = %d WHERE operator_id = %d;', $newId, $delta + $oldId));
  		$this->log(sprintf('UPDATE ticket_tracking SET actor_id = %d WHERE actor_id = %d;', $newId, $delta + $oldId));
  	}
  }
}
