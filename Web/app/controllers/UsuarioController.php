<?php
class UsuarioController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        header("Location: " . BASE_URL . "usuario/login");
        exit();
    }

    // 🔹 Iniciar sesión (texto plano)
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email_usuario', FILTER_SANITIZE_EMAIL);
            $email = $email ? strtolower(trim($email)) : '';
            $pass = isset($_POST['contrasenia_usuario']) ? trim($_POST['contrasenia_usuario']) : '';

            if (empty($email) || empty($pass)) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Ingrese correo y contraseña.'];
                header('Location: ' . BASE_URL . 'usuario/login');
                exit();
            }

            $usuarioModel = $this->model('Usuario');
            $user = $usuarioModel->autenticar($email, $pass);

            if ($user) {
                $_SESSION['usuario_id'] = (int) $user['id_usuario'];
                $_SESSION['usuario_email'] = $user['email_usuario'];
                $_SESSION['usuario_rol'] = (int) $user['rol_usuario'];
                $_SESSION['usuario_nombre'] = $user['nombre_usuario'] ?? '';

                // Redirigir según el rol
                if ($_SESSION['usuario_rol'] === 1) {
                    header('Location: ' . BASE_URL . 'admin');
                } else {
                    header('Location: ' . BASE_URL);
                }
                exit();
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Credenciales incorrectas.'];
                header('Location: ' . BASE_URL . 'usuario/login');
                exit();
            }
        }

        $this->view('usuario/login');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL);
        exit();
    }

    // 🔹 Registro
    public function registro()
    {
        $this->view('usuario/registro', ['titulo' => 'Registro de usuario']);
    }

    // 🔹 Procesar registro
    public function procesarRegistro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = $this->model('Usuario');

            $usuarioModel->nombre_usuario = $_POST['nombre'] ?? '';
            $usuarioModel->apellido_usuario = $_POST['apellido'] ?? '';
            $usuarioModel->email_usuario = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
            $usuarioModel->contrasenia_usuario = $_POST['contrasenia'] ?? ''; // 👉 Texto plano
            $usuarioModel->direccion_usuario = $_POST['direccion'] ?? '';
            $usuarioModel->telefono_usuario = $_POST['telefono'] ?? null;
            $usuarioModel->rol_usuario = 0; // Cliente por defecto

            if ($usuarioModel->registrar()) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuario registrado correctamente.'];
                header("Location: " . BASE_URL . "usuario/login");
                exit();
            } else {
                $this->view('usuario/registro', [
                    'titulo' => 'Registro de usuario',
                    'error' => '❌ Error al registrar usuario.'
                ]);
            }
        }
    }

    // 🔹 Perfil
    public function perfil()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }

        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->obtenerPorId($_SESSION['usuario_id']);

        $this->view('usuario/perfil', [
            'titulo' => 'Mi perfil',
            'usuario' => $usuario
        ]);
    }

    // 🔹 Listar usuarios (para el dashboard de admin)
    public function listar()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 1) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }

        $usuarioModel = $this->model('Usuario');
        $usuarios = $usuarioModel->obtenerTodos();

        $this->view('admin/usuarios', ['usuarios' => $usuarios]);
    }

    // 🔹 Formulario para crear un usuario manualmente (opcional)
    public function crear()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 1) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }

        $this->view('usuario/form', ['modo' => 'crear']);
    }

    // 🔹 Guardar un nuevo usuario (desde panel admin)
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = $this->model('Usuario');

            $usuarioModel->nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $usuarioModel->apellido_usuario = trim($_POST['apellido_usuario'] ?? '');
            $usuarioModel->email_usuario = strtolower(trim($_POST['email_usuario'] ?? ''));
            $usuarioModel->contrasenia_usuario = trim($_POST['contrasenia_usuario'] ?? ''); // ✅ importante
            $usuarioModel->direccion_usuario = trim($_POST['direccion_usuario'] ?? '');
            $usuarioModel->telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
            $usuarioModel->rol_usuario = (int) ($_POST['rol_usuario'] ?? 0); // ✅ cliente por defecto

            if ($usuarioModel->registrar()) {
                header('Location: ' . BASE_URL . 'usuario/listar');
                exit();
            } else {
                die('❌ Error al guardar el usuario.');
            }
        }
    }


    // 🔹 Editar usuario (mostrar formulario)
    public function editar($id = null)
    {
        if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
            header('Location: ' . BASE_URL . 'usuario/listar');
            exit();
        }

        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->obtenerPorId((int) $id);

        if (!$usuario) {
            die('Usuario no encontrado.');
        }

        $this->view('usuario/form', [
            'usuario' => $usuario,
            'modo' => 'editar'
        ]);
    }

    // 🔹 Actualizar usuario
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = $this->model('Usuario');
            $usuarioModel->id_usuario = (int) $_POST['id_usuario'];

            $usuarioModel->nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $usuarioModel->apellido_usuario = trim($_POST['apellido_usuario'] ?? '');
            $usuarioModel->email_usuario = strtolower(trim($_POST['email_usuario'] ?? ''));
            $usuarioModel->direccion_usuario = trim($_POST['direccion_usuario'] ?? '');
            $usuarioModel->telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
            $usuarioModel->rol_usuario = (int) ($_POST['rol_usuario'] ?? 0);

            if ($usuarioModel->actualizar()) {
                header('Location: ' . BASE_URL . 'usuario/listar');
                exit();
            } else {
                die('❌ Error al actualizar usuario.');
            }
        }
    }


    // 🔹 Eliminar usuario
    public function eliminar($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $usuarioModel = $this->model('Usuario');
            if ($usuarioModel->eliminar((int) $id)) {
                header('Location: ' . BASE_URL . 'usuario/listar');
                exit();
            } else {
                die('❌ No se pudo eliminar el usuario.');
            }
        }
    }

}
?>