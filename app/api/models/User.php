<?php


require_once dirname(__DIR__) . '../../core/Database.php';

class User
{
    // Propiedades del usuario
    private $id;
    private $completeName;
    private $email;
    private $phone;
    private $role;
    private $createdAt;

    // Constructor
    public function __construct($id = null, $completeName = null, $email = null, $phone = null, $role = null, $createdAt = null)
    {
        $this->id = $id;
        $this->completeName = $completeName;
        $this->email = $email;
        $this->phone = $phone;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    // Método para crear un nuevo usuario
    public static function create($completeName, $email, $password, $phone, $role)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO users (complete_name_user, email_user, password_user, phone_user, role_user) VALUES (?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("sssss", $completeName, $email, $hashedPassword, $phone, $role);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $completeName, $email, $phone, $role);
        } else {
            throw new Exception("Error al crear el usuario: " . $stmt->error);
        }
    }

    // Método para obtener todos los usuarios
    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM users");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = [
                    'id' => $row['id_user'],
                    'completeName' => $row['complete_name_user'],
                    'email' => $row['email_user'],
                    'phone' => $row['phone_user'],
                    'role' => $row['role_user'],
                    'createdAt' => $row['created_at_user']
                ];
            }
            return $users;
        } else {
            throw new Exception("Error al obtener los usuarios: " . $stmt->error);
        }
    }

    // Método para obtener un usuario por ID
    public static function getById($id)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_user'], $row['complete_name_user'], $row['email_user'], $row['phone_user'], $row['role_user'], $row['created_at_user']);
            } else {
                throw new Exception("Usuario no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el usuario: " . $stmt->error);
        }
    }

    // Método para actualizar un usuario sin tener en cuenta la contraseña
    public function updateWithoutPassword()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE users SET complete_name_user = ?, email_user = ?, phone_user = ?, role_user = ? WHERE id_user = ?");
        $stmt->bind_param("ssssi", $this->completeName, $this->email, $this->phone, $this->role, $this->id);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el usuario: " . $stmt->error);
        }
    }

    // Método para actualizar solo la contraseña del usuario
    public function updatePassword($currentPassword, $newPassword)
    {
        // Verificar la contraseña actual
        if (!$this->checkPassword($currentPassword)) {
            throw new Exception("La contraseña actual es incorrecta");
        }

        $mysqli = Database::getInstanceDB();
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $mysqli->prepare("UPDATE users SET password_user = ? WHERE id_user = ?");
        $stmt->bind_param("si", $hashedPassword, $this->id);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la contraseña: " . $stmt->error);
        }
    }

    // Método para verificar la contraseña actual
    public function checkPassword($password)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT password_user FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $this->id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return password_verify($password, $row['password_user']);
            } else {
                throw new Exception("Usuario no encontrado");
            }
        } else {
            throw new Exception("Error al verificar la contraseña actual: " . $stmt->error);
        }
    }

    // Método para eliminar un usuario
    public static function delete($id)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el usuario: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCompleteName()
    {
        return $this->completeName;
    }

    public function setCompleteName($completeName)
    {
        $this->completeName = $completeName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
