<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\Canale;

use Universibo\Bundle\LegacyBundle\Entity\CanaleRepository;

class CanaleRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @var CanaleRepository
     */
    private $repo;

    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->repo = new CanaleRepository($this->db);
    }
    
    public function testFind()
    {
        $class = 'Universibo\\Bundle\\LegacyBundle\\Entity\\Canale';
        $channel = $this->repo->find(1);
        
        $this->assertInstanceOf($class, $channel);
        $this->assertEquals(1, $channel->getIdCanale());
        $this->assertEquals(1, $channel->getTipoCanale());
        $this->assertEquals('Homepage', $channel->getNome());
        $this->assertEquals(0, $channel->getVisite());
    }
    
    public function testAddVisite()
    {
        $channel = $this->repo->find(1);
        
        $expected = $channel->getVisite() + 1;
        $this->repo->addVisite($channel);
        
        $this->assertEquals($expected, $channel->getVisite());
        
        $channel = $this->repo->find(1);
        
        $this->assertEquals($expected, $channel->getVisite());
    }
    
    public function testInsert()
    {
        $channel = new Canale(0, $permessi = 64, $ultima_modifica = time(), $tipo_canale = Canale::CDEFAULT, $immagine = 'hello.jpg', $nome = 'Hello World', $visite = 0, $news_attivo = true, $files_attivo = true, $forum_attivo = false, $forum_forum_id = null, $forum_group_id = null, $links_attivo = true, $files_studenti_attivo = true);
        
        $this->repo->insert($channel);
        
        $this->assertGreaterThan(0, $id = $channel->getIdCanale());
        
        $channel = $this->repo->find($id);
        
        $this->assertEquals($id, $channel->getIdCanale());
        $this->assertEquals($permessi, $channel->getPermessi());
        $this->assertEquals($ultima_modifica, $channel->getUltimaModifica());
        $this->assertEquals($tipo_canale, $channel->getTipoCanale());
        $this->assertEquals($immagine, $channel->getImmagine());
        $this->assertEquals($nome, $channel->getNome());
        $this->assertEquals($visite, $channel->getVisite());
        $this->assertEquals($news_attivo, $channel->getServizioNews());
        $this->assertEquals($files_attivo, $channel->getServizioFiles());
        $this->assertEquals($forum_attivo, $channel->getServizioForum());
        $this->assertEquals($links_attivo, $channel->getServizioLinks());
        $this->assertEquals($files_studenti_attivo, $channel->getServizioFilesStudenti());
        $this->assertEquals($forum_forum_id, $channel->getForumForumId());
        $this->assertEquals($forum_group_id, $channel->getForumGroupId());
    }
    
    public function testUpdate()
    {
        $channel = $this->repo->find($id = 1);
        
        $channel->setServizioNews($news_attivo = !$channel->getServizioNews());
        $channel->setPermessi($permessi = ($channel->getPermessi() + 1));
        $channel->setUltimaModifica($ultima_modifica = ($channel->getUltimaModifica() + 1));
        
        $this->repo->update($channel);
        $channel = $this->repo->find($id);
        
        $this->assertEquals($id, $channel->getIdCanale());
        $this->assertEquals($permessi, $channel->getPermessi());
        $this->assertEquals($ultima_modifica, $channel->getUltimaModifica());
        $this->assertEquals($news_attivo, $channel->getServizioNews());
        
        $this->markTestIncomplete('Should check all parameters');
    }
}
