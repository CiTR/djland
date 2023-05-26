<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Log;
use App\MembershipYear;

class FixMembershipYear2022 extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fixMembershipYear2022 
                            {--debug : Output info and stop after one entry}
                            {--id=}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Changes MembershipYear entries to associate them with their created date. This undoes an error made in 2021';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $debug = $this->option('debug') ? true : false;
    $change_month = 5;

    $fixed_count = 0;

    // If ID is used, only select that one model but put it in a collection
    if ($this->option('id')) {
      $membership_years = MembershipYear::where('id', $this->option('id'))->get();

      if ($debug) {
        var_dump($membership_years);
      }
    } else {
      $membership_years = MembershipYear::all();
    }

    // Filter out the years that are fine
    $filtered = $membership_years->filter(function ($ms_year) use ($change_month) {
      $create_date = $ms_year->create_date;
      $month = intval($create_date->month);
      $year = intval($create_date->year);

      $current_mem_year = $ms_year->membership_year;
      $current_mem_year = substr($current_mem_year, 0, 4);
      $current_mem_year = intval($current_mem_year);

      if ($change_month > $month) {
        $year = $year - 1;
      }

      return $current_mem_year != $year;
    });

    $this->line('Beginning Fix of Year for ' . $filtered->count() . ' Entries');

    sleep(5); // Wait 5 seconds so you can cancel if you need to

    foreach ($filtered as $ms_year) {
      $this->line('ID: ' . $ms_year->id);

      $create_date = $ms_year->create_date;
      $month = intval($create_date->month);
      $year = intval($create_date->year);

      $current_mem_year = $ms_year->membership_year;
      $current_mem_year = substr($current_mem_year, 0, 4);
      $current_mem_year = intval($current_mem_year);

      if ($change_month > $month) {
        $year--;
      }

      if ($debug) {
        // var_dump(array(
        //     'month' => $month,
        //     'year' => $year,
        //     'change_month' => $change_month,
        //     'current_mem_year' => $current_mem_year,
        // ));
      }

      if ($current_mem_year != $year) {
        $next_year = $year + 1;
        $fixed_mem_year = $year . '/' . $next_year;

        $ms_year->membership_year = $fixed_mem_year;

        if ($debug) {
          $this->line('fixed_mem_year:');
          var_dump($fixed_mem_year);
          $this->line('Saving...');
        }

        $save = $ms_year->save();

        if ($debug) {
          var_dump($save);
        }

        if ($save) {
          $this->line('Entry updated');
          $fixed_count++;

          if ($debug) {
            // var_dump($ms_year);
            // $this->line('Exiting...');
            // exit;
            var_dump(array(
              'id' => $ms_year->id,
              'month' => $month,
              'year' => $year,
              'change_month' => $change_month,
              'current_mem_year' => $current_mem_year,
              'ms_year_current' => $ms_year->membership_year,
              'ms_year_create_date' => $ms_year->create_date,
              'isDirty' => $ms_year->isDirty(),
            ));
          }
        }
      } elseif ($debug) {
        $this->line('Entry okay. Continuing...');
        // continue;
      }

      if ($debug) {
        // var_dump($ms_year);
        // $this->line('Exiting...');
        // exit;
      }

      continue;
    }

    $this->line('Fixed ' . $fixed_count . ' entries\' years');

    // Begin fixing duplicate entries
    $this->line('Begin fixing duplicate entries');

    sleep(5); // 5 second power nap

    $delete_count = 0;

    while ($membership_years->count() > 0) {
      // Pop a model off the collection
      $ms_year = $membership_years->pop();

      // Create new collection of duplicates
      $duplicates = $membership_years->filter(function ($ms_dupe) use ($ms_year) {

        if ($ms_dupe->member_id !== $ms_year->member_id) {
          return false;
        }

        if ($ms_dupe->membership_year !== $ms_year->membership_year) {
          return false;
        }

        return true;
      });

      if ($duplicates->count()) {
        $this->line('Duplicates of ' . $ms_year->id . ' found. Detected ' . $duplicates->count() . ' dupes');

        // Remove duplicates from the master collection
        $membership_years = $membership_years->filter(function ($ms_not_dupe) use ($ms_year) {

          if ($ms_not_dupe->member_id !== $ms_year->member_id) {
            return true;
          }

          if ($ms_not_dupe->membership_year !== $ms_year->membership_year) {
            return true;
          }

          return false;
        });

        // Delete duplicates
        foreach ($duplicates as $dupe) {
          $this->line('Deleting ' . $dupe->id);
          $dupe->delete();
          $delete_count++;
        }
      }
    }

    $this->line($delete_count . " duplicates deleted");
  }
}
