<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Buku extends RestController
{
	public function __construct()
	{
		parent::__construct();
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
			], self::HTTP_BAD_REQUEST);
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
				], self::HTTP_BAD_REQUEST);
			}
		}
	}

	public function index_post()
	{
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
			], self::HTTP_BAD_REQUEST);
		}
	}

	public function index_put()
	{
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
			], self::HTTP_BAD_REQUEST);
		}
	}
}
