<?php

namespace Element\Repositories;

class EmUserRepository extends BaseRepository
{
	public function first()
	{
		return $this->db()->createQueryBuilder()
            ->select('*')
            ->from('em_users')
            ->where('id = :id')
            ->setParameter('id', 1)
            ->executeQuery()
            ->fetchAssociative();
	}
}