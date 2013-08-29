<?php

class solImportusersTask extends sfBaseTask {
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
        $this->name = 'import-users';
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

	Doctrine_Query::create()->delete('Actor a')->execute();

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
        foreach ($conn->query($sql) as $row) {
            $actor = new Actor();
            $actor->phone_no = preg_replace('/[^0-9]/', '', $row['phone']);
            $actor->name = $row['name'];
            $actor->firstname = '';
            $actor->adresse = '';
            $actor->zip = '';
            $actor->city = '';
            $actor->email = $row['email'];
            $actor->card_no = $row['card_no']; 
            $actor->sol_count = 0;
            $actor->euro_count = 0;
            $actor->password = md5('sol');
            $actor->latitude = $row['lat'];
            $actor->longitude = $row['lng'];
            $actor->perm_tracking = true;
            $actor->perm_stats = true;
            $actor->perm_export = true;
            $actor->save();
            $this->log('Imported: ' . $actor->name);
        }
    }
}
