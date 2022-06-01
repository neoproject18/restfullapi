<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/Login.php';

class Buku extends Login
{
	public function __construct()
	{
		parent::__construct();
		$this->cektoken();
		$this->load->model('m_buku', 'buku');
		// $this->methods['index_get']['limit'] = 2;
		// $this->methods['index_delete']['limit'] = 2;
	}

	public function index_get()
	{
		$id = $this->get('id_buku');
		if ($id === null)
			$buku = $this->buku->getBuku();
		else
			$buku = $this->buku->getBuku($id);

		if ($buku) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil ditemukan',
				'count' => count($buku),
				'result' => $buku
			], self::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan',
			], self::HTTP_NOT_FOUND);
		}
	}

	public function index_delete()
	{
		$id = $this->delete('id_buku');
		if ($id === null) {
			$this->response([
				'status' => false,
				'message' => 'Masukkan Id',
			], self::HTTP_NOT_FOUND);
		} else {
			if ($this->buku->deleteBuku($id) > 0) {
				$this->response([
					'status' => true,
					'message' => 'Buku berhasil dihapus!',
				], self::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Data tidak ditemukan!',
				], self::HTTP_INTERNAL_ERROR);
			}
		}
	}

	public function index_post()
	{
		if ($this->_validationCheck() == false) {
			$this->response([
				'status' => false,
				'message' => strip_tags(validation_errors())
			], self::HTTP_FORBIDDEN);
		} else {
			$data = [
				"judul_buku" => $this->post('judul'),
				"tahun_terbit" => $this->post('tahun'),
				"penerbit" => $this->post('penerbit'),
				"penulis" => $this->post('penulis'),
				"id_kategori" => $this->post('id_kategori'),
				"jumlah" => $this->post('jumlah')
			];

			if ($this->buku->insertBuku($data) > 0) {
				$this->response([
					'status' => true,
					'message' => 'Buku berhasil ditambahkan!',
				], self::HTTP_CREATED);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Buku gagal ditambahkan!',
				], self::HTTP_INTERNAL_ERROR);
			}
		}
	}

	public function index_put()
	{
		$this->form_validation->set_data($this->put());
		if ($this->_validationCheck() == false) {
			$this->response([
				'status' => false,
				'message' => strip_tags(validation_errors())
			], self::HTTP_FORBIDDEN);
		} else {
			$id = $this->put('id_buku');
			$data = [
				"judul_buku" => $this->put('judul'),
				"tahun_terbit" => $this->put('tahun'),
				"penerbit" => $this->put('penerbit'),
				"penulis" => $this->put('penulis'),
				"id_kategori" => $this->put('id_kategori'),
				"jumlah" => $this->put('jumlah')
			];

			if ($this->buku->updateBuku($data, $id) > 0) {
				$this->response([
					'status' => true,
					'message' => 'Buku berhasil diperbarui!',
				], self::HTTP_CREATED);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Buku gagal diperbarui!',
				], self::HTTP_INTERNAL_ERROR);
			}
		}
	}

	private function _validationCheck()
	{
		$this->form_validation->set_rules(
			'judul',
			'Judul Buku',
			'required',
			array('required' => 'Silahkan masukkan judul buku!')
		);

		$this->form_validation->set_rules(
			'tahun',
			'Tahun Terbit',
			'required',
			array('required' => 'Silahkan masukkan tahun terbit!')
		);

		$this->form_validation->set_rules(
			'penerbit',
			'Penerbit Buku',
			'required',
			array('required' => 'Silahkan masukkan penerbit buku!')
		);

		$this->form_validation->set_rules(
			'penulis',
			'Penulis Buku',
			'required',
			array('required' => 'Silahkan masukkan penulis buku!')
		);

		$this->form_validation->set_rules(
			'id_kategori',
			'Kategori Buku',
			'required',
			array('required' => 'Silahkan masukkan kategori buku!')
		);

		$this->form_validation->set_rules(
			'jumlah',
			'Jumlah Buku',
			'required',
			array('required' => 'Silahkan masukkan jumlah buku!')
		);

		return $this->form_validation->run();
	}
}