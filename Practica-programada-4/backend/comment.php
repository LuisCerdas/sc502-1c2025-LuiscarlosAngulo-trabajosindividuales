<?php
require('db.php');

function createComment($task_id, $comment)
{
    global $pdo;
    try {
        $sql = "INSERT INTO comment (task_id, comment) VALUES (:task_id, :comment)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'task_id' => $task_id,
            'comment' => $comment
        ]);
        return $pdo->lastInsertId();

    } catch (Exception $e) {
        echo $e->getMessage();
        return 0;
    }
}

function getCommentsByTask($task_id)
{
    try {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM comment WHERE task_id = :task_id");
        $stmt->execute(['task_id' => $task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        echo "Error al obtener los comentarios" . $ex->getMessage();
        return [];
    }
}

function editComment($id, $comment)
{
    global $pdo;
    try {
        $sql = "UPDATE comment SET comment = :comment WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'comment' => $comment,
            'id' => $id
        ]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}

function deleteComment($id)
{
    global $pdo;
    try {
        $sql = "DELETE FROM comment WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["id" => $id]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}

function validateCommentInput($input)
{
    if (isset($input['task_id'], $input['comment']) && !empty($input['task_id']) && !empty($input['comment'])) {
        return true;
    }
    return false;
}


$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');

function getJsonInput()
{
    return json_decode(file_get_contents("php://input"), true);
}

session_start();

if (isset($_SESSION["user_id"])) {
    try {


        $userId = $_SESSION["user_id"];
        switch ($method) {
            case 'GET':
                    $comments = getCommentsByTask('task_id');
                    echo json_encode($comments);
              
                break;

            case 'POST':
                $input = getJsonInput();
                if (validateCommentInput($input)) {
                    $commentId = createComment($input['task_id'], $input['comment']);
                    if ($commentId > 0) {
                        http_response_code(201);
                        echo json_encode(["message" => "Comentario creado exitosamente. Id: " . $commentId]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => "Error al crear el comentario"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Datos insuficientes o incorrectos"]);
                }
                break;

            case 'PUT':
                $input = getJsonInput();
                if (isset($_GET['id']) && validateCommentInput($input)) {
                    if (editComment($_GET['id'], $input['comment'])) {
                        http_response_code(200);
                        echo json_encode(['message' => "Comentario actualizado exitosamente"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => "Error interno al actualizar el comentario"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Datos insuficientes']);
                }
                break;

            case 'DELETE':
                if (isset($_GET['id'])) {
                    if (deleteComment($_GET['id'])) {
                        http_response_code(200);
                        echo json_encode(['message' => "Comentario eliminado exitosamente"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => "Error interno al eliminar el comentario"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => "Petición inválida"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
        }
    } catch (Exception $exp) {
        http_response_code(500);
        echo json_encode(['error' => "Error al procesar la solicitud"]);
    }
} else {
    http_response_code(401);
    echo json_encode(["error" => "Sesión no activa"]);
}