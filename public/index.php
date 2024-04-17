<?php
/* 
Esta implementação contém as operações de inserção e remoção em uma Árvore AVL. 
Além disso, inclui a função inorder() para percorrer a árvore em ordem, 
demonstrando seu conteúdo.
*/

class Node {
    public $value;
    public $left;
    public $right;
    public $height;

    public function __construct($value) {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
        $this->height = 1;
    }
}

class AVLTree {
    private $root;

    public function __construct() {
        $this->root = null;
    }

    private function height($node): int {
        return $node ? $node->height : 0;
    }

    private function updateHeight($node) {
        $node->height = max($this->height($node->left), $this->height($node->right)) + 1;
    }

    private function rotateRight($y) {
        $x = $y->left;
        $y->left = $x->right;
        $x->right = $y;
        $this->updateHeight($y);
        $this->updateHeight($x);
        return $x;
    }

    private function rotateLeft($x) {
        $y = $x->right;
        $x->right = $y->left;
        $y->left = $x;
        $this->updateHeight($x);
        $this->updateHeight($y);
        return $y;
    }

    public function insert($value) {
        $this->root = $this->insertRec($this->root, $value);
    }

    private function insertRec($node, $value) {
        if ($node == null) {
            return new Node($value);
        }

        if ($value < $node->value) {
            $node->left = $this->insertRec($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->insertRec($node->right, $value);
        } else {
            return $node;
        }

        $this->updateHeight($node);

        $balance = $this->height($node->left) - $this->height($node->right);

        // Left Left Case
        if ($balance > 1 && $value < $node->left->value) {
            return $this->rotateRight($node);
        }

        // Right Right Case
        if ($balance < -1 && $value > $node->right->value) {
            return $this->rotateLeft($node);
        }

        // Left Right Case
        if ($balance > 1 && $value > $node->left->value) {
            $node->left = $this->rotateLeft($node->left);
            return $this->rotateRight($node);
        }

        // Right Left Case
        if ($balance < -1 && $value < $node->right->value) {
            $node->right = $this->rotateRight($node->right);
            return $this->rotateLeft($node);
        }

        return $node;
    }

    public function remove($value) {
        $this->root = $this->removeRec($this->root, $value);
    }

    private function removeRec($node, $value) {
        if ($node == null) {
            return $node;
        }

        if ($value < $node->value) {
            $node->left = $this->removeRec($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->removeRec($node->right, $value);
        } else {
            if ($node->left == null || $node->right == null) {
                $temp = ($node->left != null) ? $node->left : $node->right;
                if ($temp == null) {
                    $temp = $node;
                    $node = null;
                } else {
                    $node = $temp;
                }
                unset($temp);
            } else {
                $temp = $this->minValueNode($node->right);
                $node->value = $temp->value;
                $node->right = $this->removeRec($node->right, $temp->value);
            }
        }

        if ($node == null) {
            return $node;
        }

        $this->updateHeight($node);

        $balance = $this->height($node->left) - $this->height($node->right);

        // Left Left Case
        if ($balance > 1 && $this->height($node->left->left) >= $this->height($node->left->right)) {
            return $this->rotateRight($node);
        }

        // Left Right Case
        if ($balance > 1 && $this->height($node->left->left) < $this->height($node->left->right)) {
            $node->left = $this->rotateLeft($node->left);
            return $this->rotateRight($node);
        }

        // Right Right Case
        if ($balance < -1 && $this->height($node->right->right) >= $this->height($node->right->left)) {
            return $this->rotateLeft($node);
        }

        // Right Left Case
        if ($balance < -1 && $this->height($node->right->right) < $this->height($node->right->left)) {
            $node->right = $this->rotateRight($node->right);
            return $this->rotateLeft($node);
        }

        return $node;
    }

    private function minValueNode($node) {
        $current = $node;
        while ($current->left != null) {
            $current = $current->left;
        }
        return $current;
    }

    public function inorder() {
        $this->inorderRec($this->root);
    }

    private function inorderRec($node) {
        if ($node != null) {
            $this->inorderRec($node->left);
            echo $node->value . " ";
            $this->inorderRec($node->right);
        }
    }
}

// Exemplo de uso
$tree = new AVLTree();
$tree->insert(10);
$tree->insert(20);
$tree->insert(30);
$tree->insert(40);
$tree->insert(50);
$tree->insert(25);

echo "Árvore AVL após inserções: ";
$tree->inorder();
echo "\n<br />";

$tree->remove(20);
$tree->remove(50);
echo "Árvore AVL após remoção dos nós com valores 20 e 50: ";
$tree->inorder();
echo "\n<br />";