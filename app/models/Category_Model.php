<?php

class Category_Model extends Database
{

	public function create($data)
	{
		$this->query("
		INSERT INTO tb_categories
		SET	name = ?, slug = ?
		");
		$this->bind(1, $data->name);
		$this->bind(2, $data->slug);
		$this->execute();

		return $this->exists($data->slug) ? true: false;
	}


	public function read($key = null)
	{
		$type = (!(int)$key) ? 'slug': 'id';
		if (empty($key))
		{
			$this->query("
				SELECT *
				FROM tb_categories
				ORDER BY name
			");
			$this->execute();
			foreach ($this->return('fetchAll', PDO::FETCH_OBJ) as $x) {
				$data['data'][] = array(
					'id'	=> $x->id,
					'slug'	=> $x->slug,
					'name'	=> $x->name,
					'count'	=> $this->num_rows("SELECT COUNT(id) FROM tb_posts WHERE category LIKE '%".$x->slug."%'")
				);
			}
			$data['empty'] = empty($data['data']) ? 1: 0;
			return $data;
		}
		else
		{
			if ($type == 'slug')
			{
				$this->query("
					SELECT *
					FROM tb_categories
					WHERE slug = ?
				");
				$this->bind(1, $key);
			} else {
				$this->query("
					SELECT *
					FROM tb_categories
					WHERE id = ?
				");
				$this->bind(1, $key);
			}

			$this->execute();
			
			$x = $this->return('fetch', PDO::FETCH_OBJ);
			$data = array(
				'id'	=> $x->id,
				'slug'	=> $x->slug,
				'name'	=> $x->name,
				'count'	=> $this->num_rows("SELECT COUNT(id) FROM tb_posts WHERE category LIKE '%".$x->slug."%'")
			);
			return $data;
		}
	}


	public function update($data)
	{
		$this->query("
		UPDATE tb_categories
		SET	name = ?, slug = ?
		WHERE id = ?
		");
		$this->bind(1, $data->name);
		$this->bind(2, $data->slug);
		$this->bind(3, $data->id);
		$this->execute();

		return $this->exists($data->slug) ? true: false;
	}


	public function delete($slug)
	{
		$this->query("DELETE FROM tb_categories WHERE slug = ?");
		$this->bind(1, $slug);
		$this->execute();
	}


	public function exists($slug)
	{
		return $this->num_rows("SELECT COUNT(id) FROM tb_categories WHERE slug = '".$slug."'");
	}
}