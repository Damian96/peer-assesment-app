<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckClosedSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for closed Sessions on midnight';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        /**
         * @var array $closing
         */
        $closing = \App\Session::whereDeadline(now()->format('Y-m-d 00:00:00.000000'))
            ->getModels();
        if (empty($closing)) return false;

        $bar = $this->output->createProgressBar(count($closing));
        $bar->start();
        $this->line("");
        foreach ($closing as $session) {
            /**
             * @var \App\Session $session
             */
            $marks = [];
            foreach ($session->groups()->getModels() as $group) {
                /**
                 * @var \App\Group $group
                 */
                foreach ($group->students()->get(['users.*']) as $student) {
                    /**
                     * @var \App\User $student
                     */
                    if ($group && $group->mark && \App\StudentSession::whereUserId($student->id)->where('session_id', '=', $session->id)->exists()) {
                        $this->line(sprintf("Group mark of %s: %d", $group->name, $group->mark));
                        try {
                            $this->info(sprintf("Calculated mark of Student %s: %d", $student->fullname, $student->calculateMark($session->id)));
                        } catch (\Throwable $e) {
                            $this->error($e->getMessage());
                        }
                    }
                }
                $this->line(sprintf("Finished mark calculation of Group %s", $group->name));
                $marks[] = $group->mark;
            }
            $session->mark_avg = array_sum($marks) / count($marks);
            try {
                $session->saveOrFail();
            } catch (\Throwable $e) {
                throw $e;
            }
            $this->line(sprintf("Finished mark calculation of Session %s", $session->title));
            $this->line(sprintf("Average class mark was: %d", $session->mark_avg));
            $bar->advance();
        }
        $bar->finish();
        $this->line("");
        return true;
    }
}
