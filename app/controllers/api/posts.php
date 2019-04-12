<?php

class Posts extends Controllers {

	public function main($slug = null)
	{
		$this->read($slug);
	}


	public function create()
	{
		$db = $this->model('Posts_Model');
		$data = (object)$_POST;
		$error = 0;

		$data->slug = empty($data->slug) ? $this->plugin->slugify($data->title) : $data->slug;

		$data->category = empty($data->category) ? 'uncategorized': $data->category;

		$data->status = (int)$data->status;

		if (empty($data->title)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Title is empty.';
		} elseif (empty($data->content)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Content is empty.';
		} elseif ($db->exists($data->slug)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Posts already exists.';
		} elseif (strlen($data->title) < 3) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Title minimum is 3.';
		} elseif (strlen($data->title) > 255) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Title maximum is 255.';
		} elseif (!$error) {
			if ($db->create($data))
			{
				$json['error'] = 0;
				$json['message'] = 'Post was successfuly created.';
			}
			else {
				$json['error'] = 1;
				$json['message'] = 'Failed to create new post.';
			}
		}

		echo json_encode($json);
	}


	public function read($slug = null)
	{
		$db = $this->model('Posts_Model');
		if (empty($slug))
		{
			echo json_encode($db->read());
		}
		else {
			echo json_encode($db->read($slug));
		}
	}


	public function update()
	{
		$db = $this->model('Posts_Model');
		$data = (object)$_POST;
		$error = 0;
		
		$data->slug = empty($data->slug) ? $this->plugin->slugify($data->title) : $data->slug;

		$data->category = empty($data->category) ? 'uncategorized': $data->category;

		$data->status = (int)$data->status;

		if (empty($data->id)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'ID is empty.';
		} elseif (empty($data->title)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Title is empty.';
		} elseif (empty($data->content)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Content is empty.';
		} elseif ($db->exists($data->slug, $data->id)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Posts already exists.';
		} elseif (strlen($data->title) < 3) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Title minimum is 3.';
		} elseif (strlen($data->title) > 255) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Title maximum is 255.';
		} elseif (!$error) {
			if ($db->update($data))
			{
				$json['error'] = 0;
				$json['message'] = 'Post was successfuly updated.';
			}
			else {
				$json['error'] = 1;
				$json['message'] = 'Failed to update post.';
			}
		}

		echo json_encode($json);
	}


	public function delete()
	{
		$db = $this->model('Posts_Model');
		$slug = @$_POST['slug'];
		$error = 0;

		if (empty($slug)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Slug is not defined.';
		} elseif (!$db->exists($slug)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Post not found.';
		} elseif (!$error) {
			$db->delete($slug);
			if ($db->exists($slug)) {
				$json['error'] = 1;
				$json['message'] = 'Failed to delete post.';
			}
			else {
				$json['error'] = 0;
				$json['message'] = 'Post was successfuly deleted.';
			}
		}

		echo json_encode($json);
	}

}