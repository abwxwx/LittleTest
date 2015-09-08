<?php

class Node{
	public $data = '';
	public $left = null;
	public $right = null;
	public $pnode = null;
	public $balfct = 0;  //平衡因子
	public $depth = 1;
	
	public function __construct($data){
		$this->data = $data;
	}
}

class BalanceTree{
	public $root = null;
	public $size = 0;

	
	public function insert($data){
		$node = new Node($data);
		
		if($this->root === null){
			$this->root = $node;
		}
		else{
			$current = $this->root;
			$pnode = '';
			
			while($current){
				
				$pnode = $current;
				
				if($current->data > $data){
					//往左插入
					$current = $current->left;
					if(empty($current)){
						$pnode->left = $node;
						break;
					}
				}
				else if($current->data < $data){
					//往右插入
					$current = $current->right;
					if(empty($current)){
						$pnode->right = $node;
						break;
					}
				}
				else{
					echo "数据已存在，不处理";
					return $this;
				}
			}
			
			$current = $node;
			$current->pnode = $pnode;
			

			//插入节点后，调整平衡因子和深度
			if($this->CalcBalance($current)){
				
				//从最后插入节点往上找父节点，直到找到平衡因子为+-2的节点，并记录路径
				$relation = $this->FindNotBalance($current);
				echo $relation;
				echo "<br/>";
				
				//如果找到不平衡节点，则此时$current 变为直到找到平衡因子为+-2的节点
				
				//不平衡部分的旋转处理
				$current = $this->Rotation($relation, $current);
				
				//参与旋转处理的三个节点中的最高级节点往上更新树的平衡因子
				//$this->root = $this->UpdateBalance($current);
				$this->CalcBalance($current);
			}
			
		}			
		
		$this->size++;
		
		return $this;
	}
	
	public function CalcBalance($bottom, $top=null){
		$node = $bottom;

		while(($node->pnode)&&($node->pnode !== $top)){
			if(($node->left) || ($node->right)){
				$leftdepth = $node->left ? $node->left->depth : 0;
				$rightdepth = $node->right ? $node->right->depth : 0;
				$node->balfct = $rightdepth - $leftdepth;
				if($node->balfct <= 0){
					$node->depth = $leftdepth + 1;
				}
				else{
					$node->depth = $rightdepth + 1;
				}
				
				if(abs($node->balfct) == 2){
					return true;
				}
			
				if($node->balfct == 0){
					break;
				}
			}
			else{
				$node->depth = 1;
				$node->balfct = 0;
			}
			
			$node = $node->pnode;
		}
		
		//调整根结点
		if(($node->left) || ($node->right)){
			$leftdepth = $node->left ? $node->left->depth : 0;
			$rightdepth = $node->right ? $node->right->depth : 0;
			$node->balfct = $rightdepth - $leftdepth;
			if($node->balfct <= 0){
				$node->depth = $leftdepth + 1;
			}
			else{
				$node->depth = $rightdepth + 1;
			}
			
			if(abs($node->balfct) == 2){
				return true;
			}
			
		}
		else{
			$node->depth = 1;
			$node->balfct = 0;
		}
		if(empty($node->pnode))
		{
			$this->root = $node;
		}

		return false;
	}
		
	public function FindNotBalance(&$node){
		$relation = '';
		//echo "FindNotBalance";
		while($node){
			if(abs($node->balfct) == 2){
				//取不平衡节点的两层三个节点做处理
				$relation = strrev(substr($relation, -2));
				return $relation;
			}
			else{
				if($node->pnode){
					if($node->pnode->left === $node){
						$relation .= 'L';
					}
					else{
						$relation .= 'R';
					}
					$node = $node->pnode;
				}
				else{
					break;
				}
			}
		}
		return false;
	}
	
	public function Rotation($relation, $node){
		$current = '';
		//根据路径选择不同的旋转方式
		switch($relation){
			case "LL":
			$current = $this->RightRotate($node);
			break;
			case "RR":
			$current = $this->LeftRotate($node);
			break;
			case "LR":
			$current = $this->LeftRightRotate($node);
			break;
			case "RL":
			$current = $this->RightLeftRotate($node);
			break;
			default:
			echo $relation;
			break;
		}
		return $current;
	}
	
	public function RightRotate($node){
		
		$Rotatenode = $node->left;

		//不平衡节点的左节点提升1级
		$Rotatenode->pnode = $node->pnode;
		if($node->pnode){
			if($node->pnode->right === $node){
				$node->pnode->right = $Rotatenode;
			}
			else{
				$node->pnode->left = $Rotatenode;
			}			
		}
		//不平衡节点的左节点变为null，
		$node->pnode = $Rotatenode;
		$node->left = null;
		
		//如果rotatenode的右节点存在，则先将右节点变为不平衡节点的左节点
		if($Rotatenode->right){
			$node->left = $Rotatenode->right;
			$Rotatenode->right->pnode = $node;
		}
		//不平衡节点变为原来左节点的右节点
	    $Rotatenode->right = $node;
		
		//更新平衡因子
		$this->CalcBalance($node, $Rotatenode->pnode);
		
		//var_dump($Rotatenode);
		echo "  RightRotate end <br/>";
		
		return $Rotatenode;
	}
	
	//与右旋互为境像
	public function LeftRotate($node){
		$Rotatenode = $node->right;
		
		//不平衡节点的右节点提升1级
		$Rotatenode->pnode = $node->pnode;
		if($node->pnode){
			if($node->pnode->right === $node){
				$node->pnode->right = $Rotatenode;
			}
			else{
				$node->pnode->left = $Rotatenode;
			}	
		}
		//不平衡节点的右节点变为null，
		$node->pnode = $Rotatenode;
		$node->right = null;
		
		//如果rotatenode的左节点存在，则先将左节点变为不平衡节点的右节点
		if($Rotatenode->left){
			$node->right = $Rotatenode->left;
			$Rotatenode->left->pnode = $node;
		}
		//不平衡节点变为原来右节点的左节点
	    $Rotatenode->left = $node;
		
		//更新平衡因子
		$this->CalcBalance($node, $Rotatenode->pnode);
		
		//var_dump($Rotatenode);
		echo "  LeftRotate end <br/>";
		
		return $Rotatenode;
	}
	
	public function RightLeftRotate($node){
		//该函数为先右旋再左旋
		$Rotatenode = $node->right->left; //最后一层的节点
		$middle = $node->right;
		
		//下面两个节点先进行右旋
		//不平衡节点的右节点的左节点提升为不平衡节点的右节点
		$node->right = $Rotatenode;
		$Rotatenode->pnode = $node;
		//原先位于第二层的右节点变为最后一层的右节点
		$middle->left = $Rotatenode->right;
		if($Rotatenode->right){
			$Rotatenode->right->pnode = $middle;
		}
		$Rotatenode->right = $middle;
		$middle->pnode = $Rotatenode;
		
		//调整平衡因子
		$this->CalcBalance($middle, $Rotatenode->pnode);
		
		//再左旋
		$Rotatenode->pnode = $node->pnode;//再提升
		//提升之后确定与父节点的左右关系
		if($node->pnode){
			if($node->pnode->right === $node){
				$node->pnode->right = $Rotatenode;
			}
			else{
				$node->pnode->left = $Rotatenode;
			}	
		}
		$node->right = $Rotatenode->left;
		if($Rotatenode->left)
		{
			$Rotatenode->left->pnode = $node;
		}
		$Rotatenode->left = $node;
		$node->pnode = $Rotatenode;
		
		//更新平衡因子	
		$this->CalcBalance($node, $Rotatenode->pnode);
		
		//var_dump($Rotatenode);
		echo "  RightLeftRotate end <br/>";
		
		return $Rotatenode;
	}
	
	public function LeftRightRotate($node){
		//该函数为先左旋再右旋
		$Rotatenode = $node->left->right; //最后一层的节点
		$middle = $node->left;
		
		//下面两个节点先进行左旋
		//不平衡节点的左节点的右节点提升为不平衡节点的左节点
		$node->left = $Rotatenode;
		$Rotatenode->pnode = $node;
		//原先位于第二层的左节点变为最后一层的左节点
		$middle->right = $Rotatenode->left;
		if($Rotatenode->left){
			$Rotatenode->left->pnode = $middle;
		}
		$Rotatenode->left = $middle;
		$middle->pnode = $Rotatenode;
		
		//调整平衡因子
		$this->CalcBalance($middle, $Rotatenode->pnode);
		
		//再右旋
		$Rotatenode->pnode = $node->pnode;//再提升
		//提升之后确定与父节点的左右关系
		if($node->pnode){
			if($node->pnode->right === $node){
				$node->pnode->right = $Rotatenode;
			}
			else{
				$node->pnode->left = $Rotatenode;
			}
		}
		$node->left = $Rotatenode->right;
		if($Rotatenode->right){
			$Rotatenode->right->pnode = $node;
		}
		$Rotatenode->right = $node;
		$node->pnode = $Rotatenode;

		//更新平衡因子
		$this->CalcBalance($node, $Rotatenode->pnode);
		
		//var_dump($Rotatenode);
		echo "  LeftRightRotate end <br/>";
		
		return $Rotatenode;
	}

}

//$data = array(16,3,7,11,9,26,18,14,15);
//$data = array(1,100,20,70,50,30,10,45);
for($i = 0; $i < 10; $i++){
	$data[] = rand(1,100);
}

print_r($data);

$bTree = new BalanceTree();

foreach($data as $value){
	$bTree->insert($value);
}

var_dump($bTree->root); exit;