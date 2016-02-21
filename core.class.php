<?php
/**
 * Główna klasa z fukcjami
 */
class DataInfo{
    
    private $db;
    
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
    /**
     * Wczytanie plików do importu
     * @param  string [$dir = 'data'] folder z plikami do wczytania
     * @return array zawiera listę plików z folderu
     */
    public function load_files($dir = 'data'){   
        $result = array();
        $files = scandir($dir);
        foreach($files as $f){
            if(!in_array($f, array('.','..')) && !preg_match('/imported/i', $f)){
                $result[] = $dir.'/'.$f;
            }
        }
        return $result;
    }
    /**
     * Funkcja wykonywania po zakończeniu wczytywaniu plików do bazy danych
     * Przenosi pliki do folderu old i zmienia ich nazwę na [poprzednia nazwa].imported_[aktualny timestamp]
     * @return boolean 
     */
    public function after_complete(){
        $files = $this->load_files();
        if(sizeof($files)>0){
            foreach($files as $f){
                $ext = pathinfo($f, PATHINFO_EXTENSION);
                $t = basename($f, $ext);
                $now = new DateTime();
                rename($f, 'old/'.$t.'imported_'.$now->getTimestamp().'.'.$ext);
            }
        }
        return true;  
    }
    /**
     * Funkcja importująca dane z plików do bazy danych
     * @return boolean 
     */
    public function insert_data(){
        $files = $this->load_files();
        if(sizeof($files)>0){
            rsort($files);
        
            foreach($files as $f){    
                if(($handle = fopen($f, 'r')) !== false){
                    $ff = explode('/', $f);
                     switch($ff[1]){
                            case 'invoices.csv':

                                $sql = "INSERT INTO invoices(idusers, amount, created_at)"; 
                                $header = fgetcsv($handle);
                                $i = 1;
                                $s = '';

                                while(($data = fgetcsv($handle)) !== false){
                                    if($i === 1){
                                        $s .= ' VALUES ("'.$data[0].'", "'.$data[1].'", "'.$data[2].'")';    
                                    }
                                    else{
                                        $s .= ', ("'.$data[0].'", "'.$data[1].'", "'.$data[2].'")';
                                    }
                                    if($i%1001 === 0){

                                        $stmt = $this->db->prepare($sql.' '.$s.';');   
                                        $stmt->execute();
                                        $i = 0;
                                        $s = '';
                                    }
                                    $i++;
                                    unset($data);
                                }
                                if($i%1001 !== 0){
                                    $stmt = $this->db->prepare($sql.' '.$s.';');   
                                    $stmt->execute();
                                    $i = 0;
                                    $s = '';
                                }
                              break;
                            case 'users.csv':
                                $sql = "INSERT INTO users(name,email,email_domain)"; 
                                $header = fgetcsv($handle);
                                $i = 1;
                                $s = '';

                                while(($data = fgetcsv($handle)) !== false){
                                $d = explode('@',$data[2]);
                                if($i === 1){
                                    $s .= ' VALUES ("'.$data[1].'", "'.$data[2].'", "'.$d[1].'")';    
                                }
                                else{
                                    $s .= ', ("'.$data[1].'", "'.$data[2].'", "'.$d[1].'")';
                                }
                                if($i%1001 === 0){

                                    $stmt = $this->db->prepare($sql.' '.$s.';');   

                                    $stmt->execute();
                                    $i = 0;
                                    $s = '';
                                }
                                    $i++;
                                 unset($data);
                                }
                                if($i%1001 !== 0){
                                    $stmt = $this->db->prepare($sql.' '.$s.';');   
                                    $stmt->execute();
                                    $i = 0;
                                    $s = '';
                                }
                                break;
                        }    
                    fclose($handle);
                }
            }
            $this->after_complete();
        }
        return true;
    }
	/**
	 * Funkcja wczytująca ilość dziennych transakcji
	 * @return array 
	 */
	public function count_daily_transactions(){
			$stmt = $this->db->prepare('SELECT DATE(created_at) AS saledate, COUNT(idinvoices) as n_trans, SUM(amount) as amount FROM invoices GROUP BY created_at ORDER BY created_at DESC');
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
	}
	/**
	 * Funkcja wczytująca ilość unikalnych użytkowników
	 * @return array 
	 */
	public function count_unique_users(){
			$stmt = $this->db->prepare('SELECT created_at as date, COUNT(DISTINCT idusers) AS u_users FROM invoices GROUP By created_at ORDER BY created_at DESC');
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
	}
	/**
	 * Funkcja wczytująca ilość użytkowników w każdej domienie mailowej
	 * @return array 
	 */
	public function count_users_mail_domain(){
			$stmt = $this->db->prepare('SELECT  email_domain as domain, COUNT(email_domain) AS e_users FROM users GROUP By email_domain');
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
	}
	/**
	 * Funkcja wczytująca ilość tansakcji użytkowników
	 * @return array 
	 */
	public function count_users_transactions(){
        
			$stmt = $this->db->prepare('SELECT (select name from users where idusers = i.idusers) as name,  COUNT(i.idusers) AS count_user_t FROM invoices i GROUP By i.idusers ORDER BY i.idusers ASC');
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
	}
	/**
	 * Funkcja wczytująca ilość tansakcji użytkowników (mających wiecej niż 3)
	 * @return array 
	 */
	public function count_users_transactions_gt3(){
			$stmt = $this->db->prepare('SELECT (select name from users where idusers = i.idusers) as name, COUNT(i.idusers) AS count_user_t FROM invoices i GROUP By i.idusers HAVING count_user_t > 3 ORDER BY i.idusers ASC');
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
	}
 	/**
	 * Funkcja wczytująca średnią wartość dziennych transakcji z ostatnich 7 dni
	 * @return array 
	 */   
	public function count_seven_days_transactions(){
			$stmt = $this->db->prepare('SELECT  t.created_at as date, ROUND(AVG(amount), 2) as avg_amount FROM invoices t WHERE t.created_at >= ((SELECT MAX(created_at) FROM invoices LIMIT 1) - INTERVAL 7 DAY) GROUP BY t.created_at ORDER BY t.created_at DESC ');
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
	} 
	/**
	 * Funkcja wczytująca ilość transakcje z ostatnich 7 dni
	 * @return array 
	 */
	public function seven_days_transactions(){
		$stmt = $this->db->prepare('SELECT t.amount as amount, t.created_at as date FROM invoices t WHERE t.created_at >= ((SELECT MAX(created_at) FROM invoices LIMIT 1) - INTERVAL 7 DAY)  ORDER BY t.created_at DESC');
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
}

?>