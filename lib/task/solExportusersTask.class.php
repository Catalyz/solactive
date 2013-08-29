<?php

class solExportusersTask extends sfBaseTask {
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        // new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));
        $this->addOptions(array(new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
                new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
                ));

        $this->namespace = 'sol';
        $this->name = 'export-users';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [sol:import-users|INFO] task does things.
Call it with:

  [php symfony sol:import-users|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        // add your code here

        $conn = new PDO("mysql:host=server2.waterproof.fr;dbname=catalyz_sol_violette", 'solviolette', 'ZH6WFM8Tf8WRNG7K');

        $sql = "SELECT
  		DISTINCT katao_user.gmap_lng as lng, katao_user.gmap_lat as lat, katao_user.phone as phone,
  		katao_user.email as email,
  		IF(katao_supplier.name IS NULL,
  			CONCAT(katao_member.first_name, ' ', katao_member.last_name),
  			katao_supplier.name) as name,
  			katao_member.card_number_sol as card_no

	FROM katao_user
		LEFT OUTER JOIN katao_member ON katao_user.katao_member_id = katao_member.id
		LEFT OUTER JOIN katao_supplier ON katao_user.katao_supplier_id = katao_supplier.id
	WHERE length(katao_user.phone) > 0
	";
    	$wrapper = new PaymobeyCardsBindingWrapper();
    	foreach ($conn->query($sql) as $row) {
    		$cardNo = preg_replace('/[^0-9]/', '', $row['card_no']);
    		$phone = preg_replace('/[^0-9]/', '', $row['phone']);
    		if(preg_match('/^0[67]/', $phone) && !empty($cardNo)){
    			$wrapper->addLine($phone, substr($cardNo, strlen($cardNo) - 4), $cardNo);
    		}
        }

    	$filePrefix = 'OfflineBindCards';
    	$filename = $wrapper->getFullFilename($filePrefix);
    	file_put_contents($filename, $wrapper->render($filePrefix));
	$message = $this->getMailer()
		->compose('shordeaux@waterproof.fr', 'shordeaux@waterproof.fr', 'Fichier PayMobey / SolActive', 'Vous trouverez en PJ le fichier avec les membres.')
		->attach(Swift_Attachment::fromPath($filename))
		->addCc('evernier@paymobey.com')
		->addCc('dlittaye@paymobey.com')
		->addCc('contact@sol-violette.info')
		;
$this->getMailer()->send($message);
    	$this->log('Generated: '.$filename);
    }
}
