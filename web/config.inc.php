<?
    require_once(__DIR__ . '/../vendor/autoload.php');
    @session_start();
	$user = new DBUser();
	$auth = new Auth('LDAP', array('version' => 3, 'basedn' => 'ou=Bolkhuis,ou=People,dc=bolkhuis,dc=tudelft,dc=nl', 'userfilter' => '(objectClass=account)'));
?>
