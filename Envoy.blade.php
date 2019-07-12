@servers(['web' => 'forge@134.209.76.207'])

@task('deploy')
    SSH_USER='bahdcoder'
    ls -la
    echo $SSH_USER
@endtask
