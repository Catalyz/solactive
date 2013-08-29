<?php

class PaymobeyCardsBindingWrapper {
	const DEFAULT_BRANCH=  'N123';
    protected function renderHeaderLine($filename, $contentLineCount)
    {
        return sprintf('01%-25s%04d%02d%02d%02d%02d%02d%06d',
            substr($filename, 0, 25),
            date('Y'), date('m'), date('d'),
            date('H'), date('i'), date('S'),
            $contentLineCount + 2
            )."\n";
    }

    protected function renderFooterLine($filename, $contentLineCount)
    {
        return sprintf('09%06d', $contentLineCount + 2)."\n";
    }

    protected function renderContent()
    {
        $result = '';
        foreach($this->lines as $line) {
            $result .= sprintf('02%-20s%-4s%-16s%-20s%010d',
                substr($line['phone'], 0, 20),
                substr($line['card_last_digits'], 0, 4),
                substr($line['card_alias'], 0, 16),
                substr($line['branch'], 0, 20),
                substr($line['trace'], 0, 10)
                )."\n";
        }
        return $result;
    }

    protected $lines = array();
    function addLine($phone, $card_last_digits, $card_alias, $branch = 'SOLVIOLETTE', $trace = null)
    {
    	if(null == $trace){
    		$trace = count($this->lines) + 1;
    	}
    	if(null == $branch){
    		$branch = self::DEFAULT_BRANCH;
    	}
        $this->lines[] = array(
            'phone' => $phone,
            'card_last_digits' => $card_last_digits,
            'card_alias' => $card_alias,
            'branch' => $branch,
            'trace' => $trace,
            );
    }

	function render($filename){
		$result = $this->renderHeaderLine($filename, count($this->lines));
		$result .= $this->renderContent();
		$result .= $this->renderFooterLine($filename, count($this->lines));
		return $result;
	}

	function getFullFilename($filename, $sequence = null){
		if(null == $sequence){
			$sequence = 1;
		}
		return sprintf('%s_%04d%02d%02d_%02d.txt', $filename, date('Y'), date('m'), date('d'), $sequence);
	}
}

//$wrapper = new PaymobeyCardsBindingWrapper();
//$wrapper->addLine('9613332452', '4521', '5213625212563', 'N123', '123456');
//$wrapper->addLine('9613332453', '6325', '5213625386523', 'N123', '123457');
//$wrapper->addLine('9613332454', '4152', '5213625474589', 'Y123', '123458');
//
//header('Content-Type: text/plain');
//$filePrefix = 'SolAccounts';
//echo $wrapper->getFullFilename($filePrefix)."\n\n";
//echo $wrapper->render($filePrefix);

?>