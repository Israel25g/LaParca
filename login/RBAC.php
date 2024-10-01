<?php

class RBAC{
    protected $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function getRolePermisions($roleid){
        $stmt = $this->pdo->prepare("SELECT p.nombre_permiso FROM permisos p INNER JOIN rol_permiso rp ON p.id = rp.permiso_id WHERE rp.rol_id = ?");
        $stmt->execute([$roleid]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function checkPermission($userId, $permission){
        $stmt = $this -> pdo -> prepare ("SELECT r.id FROM roles r INNER JOIN usuarios u ON r.id = u.rol_id WHERE u.id = ?");
        $stmt -> execute([$userId]);
        $roleId = $stmt -> fetchColumn();

        $permissions = $this -> getRolePermisions($roleId);
        return in_array($permission, $permissions);
    }
}
