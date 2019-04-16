<?php

class Posts_Model extends Database
{

	public function create($data)
	{
		$this->query("
		INSERT INTO tb_posts
		SET	title = ?, text = ?, slug = ?, category = ?, status = ?, created_at = ?
		");
		$this->bind(1, $data->title);
		$this->bind(2, $data->content);
		$this->bind(3, $data->slug);
		$this->bind(4, $data->category);
		$this->bind(5, $data->status);
		$this->bind(6, time());
		$this->execute();

		return $this->exists($data->slug) ? true: false;
	}


	public function read($key = null)
	{
		$type = (!(int)$key) ? 'slug': 'id';
		if (empty($key))
		{
			// pagination
			$nums = $this->num_rows("SELECT COUNT(id) FROM tb_posts");
			$limit = 10;
			$pages = ceil($nums/$limit);
			$get = (int)json_decode(file_get_contents("php://input"))->get;
			// $get = $_POST['get'];
			$get = !$get ? 1: $get;
			$offset = ($get - 1) * $limit;

			$data = [];
			$data['pagination'] = array(
				'totalPosts'		=> $nums,
				'totalPages'		=> $pages,
				'currentPage'		=> $get,
				'perPage'				=> $limit,
				'offset'				=> $offset
			);

			$this->query("
				SELECT *
				FROM tb_posts
				ORDER BY created_at DESC
				LIMIT $offset, $limit
			");
			$this->execute();
			$posts = $this->return('fetchAll', PDO::FETCH_OBJ);
			
			foreach ($posts as $x)
			{
				$data['data'][] = array(
					'id'			=> $x->id,
					'title'			=> $x->title,
					'time'			=> date('F d, Y', $x->created_at),
					'slug'			=> $x->slug,
					'content'		=> $x->content,
					'category'	=> $x->category,
					'status'		=> $x->status
				);
			}
			$data['empty'] = empty($data['data']) ? 1: 0;

			return $data;
		}
		elseif ($type == 'slug')
		{
			$this->query("
				SELECT *
				FROM tb_posts
				WHERE slug = ?
			");
			$this->bind(1, $key);
			$this->execute();
			return $this->return('fetch', PDO::FETCH_OBJ);
		}
		elseif ($type == 'id') {
			$this->query("
				SELECT *
				FROM tb_posts
				WHERE id = ?
			");
			$this->bind(1, $key);
			$this->execute();
			return $this->return('fetch', PDO::FETCH_OBJ);
		}
	}


	public function update($data)
	{
		$this->query("
		UPDATE tb_posts
		SET	title = ?, text = ?, slug = ?, category = ?, status = ?, updated_at = ?
		WHERE id = ?
		");
		$this->bind(1, $data->title);
		$this->bind(2, $data->content);
		$this->bind(3, $data->slug);
		$this->bind(4, $data->category);
		$this->bind(5, $data->status);
		$this->bind(6, time());
		$this->bind(7, $data->id);
		$this->execute();

		return $this->exists($data->slug) ? true: false;
	}


	public function delete($slug)
	{
		$this->query("DELETE FROM tb_posts WHERE slug = ?");
		$this->bind(1, $slug);
		$this->execute();
	}


	public function exists($slug, $id = null)
	{
		if (empty($id))
		{
			return $this->num_rows("SELECT COUNT(id) FROM tb_posts WHERE slug = '".$slug."'");
		}
		else {
			return $this->num_rows("SELECT COUNT(id) FROM tb_posts WHERE slug = '".$slug."' AND id != ". $id);	
		}
	}
}