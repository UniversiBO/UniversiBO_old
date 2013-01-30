class universibo_init
{
    exec {'load-forum':
        command => "cat /vagrant/vendor/universibo/forum-bundle/Universibo/Bundle/ForumBundle/Tests/Resources/sql/{structure,data}-postgres.sql | sed 's/OWNER TO .*/OWNER TO universibo;/' | su - postgres -c 'psql universibo_forum3'",
        unless => "test `/vagrant/vagrant/scripts/check.forumtables.php` -gt 0"
    }
}
