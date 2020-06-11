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
    protected $description = 'Check for closed Sessions on today at midnight (00:00)';

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
     * Calculate the marks of the closing Sessions
     * @TODO: add API endpoint to manually handle this command
     * @TODO: add Stack/Monolog Logger https://laravel.com/docs/7.x/logging
     *
     * @param bool $silent
     * @return bool
     * @throws \Throwable
     */
    public function handle($silent = false)
    {
        $closed = \App\Session::whereDeadline(now()->format('Y-m-d 00:00:00.000000'))
            ->getModels();
        if (empty($closed)) {
            $this->info(sprintf("Ran %s command, and there were no closed Sessions.", __CLASS__));
            return false;
        }

        if (!$silent) {
            $bar = $this->output->createProgressBar(count($closed));
            $bar->start();
            $this->line("");
        }
        foreach ($closed as $session) {
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
                    } else {
                        $this->line(sprintf("Group '%s' has not been yet marked by the professor. skipping..", $group->title));
                    }
                }
                $marks[] = $group->mark;
            }
            $session->mark_avg = array_sum($marks) / count($marks);
            try {
                $session->saveOrFail();
            } catch (\Throwable $e) {
                throw $e;
            }
            if (!$silent) {
                $this->line(sprintf("Finished mark calculation of Session %s", $session->title));
                $this->line(sprintf("Average class mark was: %d", $session->mark_avg));
                $bar->advance();
            }
        }
        if (!$silent) {
            $bar->finish();
            $this->line("");
        }

        return true;
    }
}
