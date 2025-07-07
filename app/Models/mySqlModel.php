<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Database\BaseConnection;

class MysqlModel extends Model
{
    // u codeigniteru vec se koristi singelton
    protected function db()
    {
        return \Config\Database::connect('default');
    }
    
    public function setup()
    {
   
    $db = $this->db();
    // -- Tabela: uloge
    $db->query("CREATE TABLE IF NOT EXISTS uloge (
        id INT AUTO_INCREMENT PRIMARY KEY,
        naziv VARCHAR(50)
    )");

    $db->query("INSERT IGNORE INTO uloge (id, naziv) VALUES
        (1, 'admin'),
        (2, 'konobar'),
        (3, 'gost')");

    // -- Tabela: korisnici
    $db->query("CREATE TABLE IF NOT EXISTS korisnici (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50),
        ime VARCHAR(50),
        prezime VARCHAR(50),
        email VARCHAR(100),
        password VARCHAR(255),
        contact VARCHAR(30),
        pol BOOLEAN,
        role_id INT DEFAULT 3 NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES uloge(id)
    )");

    $result = $db->query("SELECT * FROM korisnici");
    if (count($result->getResultArray()) === 0) {
        $db->query("INSERT INTO korisnici (username, ime, prezime, email, password, contact, pol)
                    VALUES ('admin1', 'Marko', 'Marković', 'marko@example.com', 'lozinka', '060123123', 1)");
    }

    // -- Tabela: kategorije
    $db->query("CREATE TABLE IF NOT EXISTS kategorije (
        id INT AUTO_INCREMENT PRIMARY KEY,
        naziv VARCHAR(100),
        opis TEXT
    )");

    // -- Tabela: proizvodi
    $db->query("CREATE TABLE IF NOT EXISTS proizvodi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        naziv VARCHAR(100),
        cena FLOAT,
        opis TEXT,
        kategorija_id INT,
        dostupno BOOLEAN,
        slika VARCHAR(50),
        FOREIGN KEY (kategorija_id) REFERENCES kategorije(id)
    )");

    // -- Tabela: stolovi
    $db->query("CREATE TABLE IF NOT EXISTS stolovi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        oznaka VARCHAR(10),
        aktivan BOOLEAN
    )");

    // -- Tabela: narudzbine
    $db->query("CREATE TABLE IF NOT EXISTS narudzbine (
        id INT AUTO_INCREMENT PRIMARY KEY,
        korisnik_id INT,
        sto_id INT,
        vreme TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(50),
        FOREIGN KEY (korisnik_id) REFERENCES korisnici(id),
        FOREIGN KEY (sto_id) REFERENCES stolovi(id)
    )");

    // -- Tabela: stavke_narudzbine
    $db->query("CREATE TABLE IF NOT EXISTS stavke_narudzbine (
        id INT AUTO_INCREMENT PRIMARY KEY,
        narudzbina_id INT,
        proizvod_id INT,
        kolicina INT,
        FOREIGN KEY (narudzbina_id) REFERENCES narudzbine(id),
        FOREIGN KEY (proizvod_id) REFERENCES proizvodi(id)
    )");

    // -- Tabela: ukupno_potroseno
    $db->query("CREATE TABLE IF NOT EXISTS ukupno_potroseno (
        user_id INT PRIMARY KEY,
        iznos FLOAT,
        FOREIGN KEY (user_id) REFERENCES korisnici(id)
    )");

    // -- Tabela: poruke
    $db->query("CREATE TABLE IF NOT EXISTS poruke (
        id INT AUTO_INCREMENT PRIMARY KEY,
        narudzbina_id INT,
        korisnik_id INT,
        tekst TEXT,
        vreme TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        procitano BOOLEAN,
        FOREIGN KEY (narudzbina_id) REFERENCES narudzbine(id),
        FOREIGN KEY (korisnik_id) REFERENCES korisnici(id)
    )");

    // -- Tabela: logovi
    $db->query("CREATE TABLE IF NOT EXISTS logovi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        korisnik_id INT,
        radnja TEXT,
        vreme TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (korisnik_id) REFERENCES korisnici(id)
    )");
    $db->query("CREATE TABLE IF NOT EXISTS korpa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    proizvod_id INT NOT NULL,
    kolicina INT DEFAULT 1,
    dodato TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id),
    FOREIGN KEY (proizvod_id) REFERENCES proizvodi(id)
    )");

    echo "Setup uspesno zavrsen!";
    }

    public function register($username, $ime, $prezime, $email, $password, $contact, $pol)
    {
        $db = $this->db();
        $db->query("INSERT INTO korisnici (`username`, `ime`, `prezime`, `email`, `password`,`contact`, `pol` )
        VALUES ('$username', '$ime', '$prezime', '$email', '$password', '$contact', $pol)");
    }
    
    public function login($username, $password){
        $db = $this->db();
        $sql = "SELECT * FROM korisnici WHERE username=?";
        $result=$db->query($sql, [$username]);
        $korisnik=$result->getRowArray();
       
        if($korisnik && $password == $korisnik['password']){
            
            session()->set([
                'user_id' => $korisnik['id'],
                'username' => $korisnik['username'],
                'ime' => $korisnik['ime'],
                'role_id' => $korisnik['role_id'],
                'isLoggedIn' => true
            ]);
            return true;
        }
        return false;
    
    }
    public function getAllUsers(){
        $db = $this->db();
        $sql = "SELECT korisnici.username, korisnici.ime, korisnici.prezime, korisnici.email, korisnici.contact, korisnici.pol, korisnici.role_id FROM korisnici";
        $result=$db->query($sql);
        $korisnici=$result->getResultArray();
        return $korisnici;
       
    }
    public function getAllUsernames(){
        $db=$this->db();
        $sql = "SELECT korisnici.username FROM korisnici";
        $result = $db->query($sql);
        $korisnici=$result->getResultArray();
        return $korisnici;
    }
    public function updateRole($username, $new_role){
        $db = $this->db();
        return $this->db()->query("UPDATE korisnici SET role_id = ? WHERE username = ?", [$new_role, $username]);
    }
    public function addCategory($ime, $opis){
        $db=$this->db();
        $sql = "INSERT INTO kategorije (`naziv`, `opis`) VALUES (?,?)";
        return $this->db()->query($sql,[$ime, $opis]);
    }
    // public function getAllCategories()
    // {
    //     $db=$this->db();
    //     $sql  = "SELECT kategorije.id, kategorije.naziv, kategorije.opis FROM kategorije";
    //     return $this->db()->query($sql)->getResultArray();
    // }
    public function addProduct($naziv, $cena, $opis, $kategorija_id,$dostupno,$img)  {
        $sql = "INSERT INTO proizvodi (`naziv`, `cena`, `opis`, `kategorija_id`,`dostupno`,`slika`) VALUES (?,?,?,?,?,?);";
        return $this->db()->query($sql,[$naziv, $cena, $opis, $kategorija_id,$dostupno,$img]); 
    }
    public function addTable($oznaka, $aktivan){
        $sql="INSERT INTO stolovi (`oznaka`, `aktivan`) VALUES (?,?);";
        return $this->db()->query($sql, [$oznaka,$aktivan]);
    }
    public function getAllOrders()
    {
    return $this->db()->query("
        SELECT n.id, n.vreme, n.status, u.username, s.oznaka AS sto
        FROM narudzbine n
        JOIN korisnici u ON n.korisnik_id = u.id
        JOIN stolovi s ON n.sto_id = s.id
        ORDER BY n.vreme DESC
    ")->getResultArray();
    }

    public function getAllMessages()
    {
        return $this->db()->query("
            SELECT p.id, p.tekst, p.vreme, p.procitano, k.username, n.id AS narudzbina_id
            FROM poruke p
            JOIN korisnici k ON p.korisnik_id = k.id
            JOIN narudzbine n ON p.narudzbina_id = n.id
            ORDER BY p.vreme DESC
        ")->getResultArray();
    }

    public function getStats()
    {
        return $this->db()->query("
            SELECT u.username, ukup.iznos
            FROM ukupno_potroseno ukup
            JOIN korisnici u ON u.id = ukup.user_id
            ORDER BY ukup.iznos DESC
            LIMIT 5
        ")->getResultArray();
    }

    public function getLogs()
    {
        return $this->db()->query("
            SELECT l.id, l.radnja, l.vreme, k.username
            FROM logovi l
            JOIN korisnici k ON k.id = l.korisnik_id
            ORDER BY l.vreme DESC
        ")->getResultArray();
    }


    //GOST

    public function getCategories(){
        return $this->db()->query("SELECT * FROM kategorije")->getResultArray();

    }
    public function getCategory($kategorija_id)
{
    return $this->db()->query("
        SELECT * FROM kategorije WHERE id = ?
    ", [$kategorija_id])->getRowArray();
}

    public function getProductsByCategory($kategorija_id)
{
    return $this->db()->query("
        SELECT * FROM proizvodi WHERE kategorija_id = ? AND dostupno = 1
    ", [$kategorija_id])->getResultArray();
}
    public function addToCart($user_id, $proizvod_id)
{
 
    $postoji = $this->db()->query("
        SELECT id, kolicina FROM korpa
        WHERE korisnik_id = ? AND proizvod_id = ?
    ", [$user_id, $proizvod_id])->getRowArray();

    if ($postoji) {
        $this->db()->query("
            UPDATE korpa SET kolicina = kolicina + 1 WHERE id = ?
        ", [$postoji['id']]);
    } else {
        $this->db()->query("
            INSERT INTO korpa (korisnik_id, proizvod_id)
            VALUES (?, ?)
        ", [$user_id, $proizvod_id]);
    }
}

public function getCart($user_id)
{
    return $this->db()->query("
        SELECT k.id AS stavka_id, p.naziv, p.cena, k.kolicina, p.id
        FROM korpa k
        JOIN proizvodi p ON k.proizvod_id = p.id
        WHERE k.korisnik_id = ?
    ", [$user_id])->getResultArray();
}
public function kreirajNarudzbinu($korisnik_id, $sto_id)
{
    $this->db()->query("
        INSERT INTO narudzbine (korisnik_id, sto_id, status)
        VALUES (?, ?, 'na čekanju')
    ", [$korisnik_id, $sto_id]);

    return $this->db()->insertID();
}

public function dodajStavkuNarudzbine($narudzbina_id, $proizvod_id, $kolicina)
{
    $this->db()->query("
        INSERT INTO stavke_narudzbine (narudzbina_id, proizvod_id, kolicina)
        VALUES (?, ?, ?)
    ", [$narudzbina_id, $proizvod_id, $kolicina]);
}


public function ocistiKorpu($korisnik_id)
{
    $this->db()->query("DELETE FROM korpa WHERE korisnik_id = ?", [$korisnik_id]);
}public function getTables()
{
    return $this->db()->query("SELECT * FROM stolovi")->getResultArray();
}

public function deleteItem($stavka_id)
{
    $this->db()->query("DELETE FROM korpa WHERE id = ?", [$stavka_id]);
}

public function getNarudzbineSaStavkama()
{
    return $this->db()->query("
        SELECT n.id AS narudzbina_id, s.oznaka AS sto, n.status, GROUP_CONCAT(p.naziv SEPARATOR ', ') AS proizvodi
        FROM narudzbine n
        JOIN stolovi s ON n.sto_id = s.id
        JOIN stavke_narudzbine sn ON sn.narudzbina_id = n.id
        JOIN proizvodi p ON sn.proizvod_id = p.id
        GROUP BY n.id
        ORDER BY n.status = 'Novo' DESC, n.vreme ASC
    ")->getResultArray();
}
public function createNarudzbinaProcedure($id, $sto_id, $proizvod_id, $kolicina)
{
    $sql = 
        "DELIMITER //

CREATE PROCEDURE kreirajNarudzbinuZaKorisnika(IN p_korisnik_id INT, IN p_sto_id INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE proizvod_id INT;
    DECLARE kolicina INT;
    DECLARE nova_narudzbina_id INT;

    DECLARE cur CURSOR FOR
        SELECT proizvod_id, kolicina FROM korpa WHERE korisnik_id = p_korisnik_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    INSERT INTO narudzbine (korisnik_id, sto_id, status)
    VALUES (p_korisnik_id, p_sto_id, 'na cekanju');

    SET nova_narudzbina_id = LAST_INSERT_ID();

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO proizvod_id, kolicina;
        IF done THEN
            LEAVE read_loop;
        END IF;

        INSERT INTO stavke_narudzbine (narudzbina_id, proizvod_id, kolicina)
        VALUES (nova_narudzbina_id, proizvod_id, kolicina);
    END LOOP;

    CLOSE cur;

    DELETE FROM korpa WHERE korisnik_id = p_korisnik_id;
END;
//

DELIMITER ;";
    $this->db()->query("CALL kreirajNarudzbinuSaStavkama(?,?,?,?)", [$id, $sto_id, $proizvod_id, $kolicina]);

}
public function pozoviProceduruNarudzbine($korisnik_id, $sto_id)
{
    $this->db()->query("CALL kreirajNarudzbinuZaKorisnika(?, ?)", [
        $korisnik_id,
        $sto_id
    ]);
}



public function azurirajStatusNarudzbine($id, $status)
{
    $this->db()->query("UPDATE narudzbine SET status = ? WHERE id = ?", [$status, $id]);
}


// pozivamo sa SELECT broj_narudzbina(2);
public function brojNarudzbinaKorisnika(){
    $sql = 'DELIMITER //
CREATE FUNCTION broj_narudzbina(p_korisnik_id INT) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE broj INT;
    SELECT COUNT(*) INTO broj FROM narudzbine WHERE korisnik_id = p_korisnik_id;
    RETURN broj;
END //
DELIMITER ;';
}
public function jeAdmin(){
    $sql='DELIMITER //
CREATE FUNCTION je_admin(p_korisnik_id INT) RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE rezultat BOOLEAN;
    SELECT (role_id = 1) INTO rezultat FROM korisnici WHERE id = p_korisnik_id;
    RETURN rezultat;
END //
DELIMITER ;';
} 
public function logPriNarudzbini(){
    $sql="DELIMITER //
CREATE TRIGGER log_narudzbina
AFTER INSERT ON narudzbine
FOR EACH ROW
BEGIN
    INSERT INTO logovi (korisnik_id, radnja) 
    VALUES (NEW.korisnik_id, CONCAT('Nova narudzbina: ', NEW.id));
END //
DELIMITER ;";
}
public function logBrisanje(){
    $sql="DELIMITER //
CREATE TRIGGER log_brisanje_korisnika
BEFORE DELETE ON korisnici
FOR EACH ROW
BEGIN
    INSERT INTO logovi (korisnik_id, radnja)
    VALUES (OLD.id, 'Korisnik obrisan.');
END //
DELIMITER ;";
}
public function event(){
    $sql = "
CREATE EVENT IF NOT EXISTS reset_korpe
ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP + INTERVAL 1 DAY
DO
  DELETE FROM korpa;
";
}

public function AzurirajUkupnoPotroseno(){
    $sql="DELIMITER //
CREATE TRIGGER azuriraj_ukupno_potroseno
AFTER INSERT ON stavke_narudzbine
FOR EACH ROW
BEGIN
    DECLARE cena DECIMAL(10,2);
    DECLARE kolicina INT;
    DECLARE ukupno DECIMAL(10,2);
    DECLARE korisnikId INT;

    SELECT p.cena INTO cena
    FROM proizvodi p
    WHERE p.id = NEW.proizvod_id;

    SET kolicina = NEW.kolicina;
    SET ukupno = cena * kolicina;

    SELECT korisnik_id INTO korisnikId
    FROM narudzbine
    WHERE id = NEW.narudzbina_id;

    IF EXISTS (
        SELECT 1 FROM ukupno_potroseno WHERE user_id = korisnikId
    ) THEN
        UPDATE ukupno_potroseno
        SET iznos = iznos + ukupno
        WHERE user_id = korisnikId;
    ELSE
        INSERT INTO ukupno_potroseno(user_id, iznos)
        VALUES (korisnikId, ukupno);
    END IF;
END //
DELIMITER ;";
}
    public function getAll()
    {

        return $this->findAll();
    }

}