<?php

namespace Tests;

use Models\Edges\Assignment;
use Models\Vertices\Paper\Paper;
use \Models\Vertices\Project\Project;
use Models\Vertices\User;
use \Models\Vertices\Variable;
use \Models\Vertices\Domain;
use \Models\Edges\SubdomainOf;
use \Models\Edges\VariableOf;



/**
 * Tests the ability to define a study, variables, domains, and use these to build a project structure.
 */
class StudyDomainVariablePaperUserAssignment extends BaseIntegrationTest {

    private $vars_per_domain = 2;
    private $subdomains_per_domain = 3;
    private $top_level_domains = 3;

    private $study;
    private $users;
    private $papers;

    function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        // Make some users
        $users = [];
        for ($i = 0; $i < 20; $i++){
            $newUser = User::register("User ".$i, " ", $i."@gmail.com", "password");
//            if (is_int($newUser)) {
//                $newUser = User::getByExample(['name' => "User ".$i,'email' => $i."@gmail.com"])[0];
//            }
            $newUser->update('active', true);

            $users[] = $newUser;

            print 'Created user ' . $users[$i]->id() . "\n";
        }

        // Make some papers
        $papers = [];
        for ($i = 0; $i < 20; $i++){
            $papers[] = Paper::create([
                'Title' =>  'Study #' . $i,
                'pmcID' =>  (rand(1000000, 9000000))
            ]);

            print 'Created paper ' . $papers[$i]->id() . "\n";
        }

        // Make a study
        $study = Project::create([
            'name' => 'study ' . rand(0, 1000)
        ]);

        $this->study = $study;
        $this->papers = $papers;
        $this->users = $users;
    }

    function testEntitiesBottomUp_Study_Domain_Variable_Paper_User_Assignment(){
        // Make some domains
        $domains = [];
        for ($i = 0; $i < $this->top_level_domains; $i++) {
            $domain = Domain::create([
                'name' => 'domain ' . rand(1000, 9999),
                'icon'  =>  'fa fa-sitemap'
            ]);
            print "created domain " . $domain->id() . "\n";
            for ($j = 0; $j < $this->subdomains_per_domain; $j++) {
                // add some subdomains
                $subdomain = Domain::create([
                    'name' => 'subdomain ' . rand(1, 9999),
                    'icon'  =>  'fa fa-sitemap'
                ]);
                print "created subdomain " . $subdomain->id() . "\n";

                foreach ($this->randomVars($this->vars_per_domain) as &$var) {
                    $subdomain->addVariable($var);
                }
                $domain->addSubdomain($subdomain);
            }
            foreach ($this->randomVars($this->vars_per_domain) as &$var) {
                $domain->addVariable($var);
            }
            $domains[] = $domain;
        }

        self::assertTrue( true );

        // Add the domains to a study
        foreach ($domains as &$d) {
            $this->study->addDomain($d);
            print "added domain to study " . $d->id() . "\n";
        }

        // Add some papers to the study
        foreach ( $this->papers as $paper ){
            $this->study->addpaper($paper);

            print "Added paper to study \n";
        }

        // Make the edges ( Double Encoded )
        foreach ( $this->papers as $paper ){
            $randomUser = $this->users[ rand(0, count($this->users)-1) ];
            $randomUser2 = $this->users[ rand(0, count($this->users)-1) ];

            $a1 = Assignment::assign( $paper, $randomUser )->id();
            $a2 = Assignment::assign( $paper, $randomUser2 )->id();

            print "Created assignment \n";
        }
    }

    // Make some variables
    private function randomVars($how_many)
    {
        $vars = [];
        for ($i = 0; $i < $how_many; $i++) {
            $var = Variable::create([
                'name' => 'variable ' . rand(1000, 9999),
                'type' => $this->randomQuestionType(),
                'icon' => 'fa fa-question'
            ]);
            array_push($vars, $var);
            print "created variable " . $var->id() . "\n";
        }
        return $vars;
    }

    private function randomQuestionType(){
        $options = [
            'text',
            'number',
            'boolean',
            'range'
        ];
        return $options[rand(0, count($options)-1)];
    }

}
