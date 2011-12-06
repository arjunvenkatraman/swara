#!/usr/bin/perl

  ####################################################
  # uploadrelais 0.1                                 #
  # by Christian Rotzoll                             #
  #                                                  #
  # christian.rotzoll@medien.uni-weimar.de           #
  # Doku unter http://www.rotzoll.de/cgiuploadrelais #
  #                                                  #
  ####################################################
  # Download-Script                                  #
  ####################################################

use CGI::Carp ('fatalsToBrowser');
use CGI ('param', 'upload');

#######################################################
# Alle Einstellungen in der 'upload.cgi'-Datei vornehmen
#######################################################


####################
## PRAMETER CHECK
####################

# - request-Parameter pruefen
$request = param('request');
if ($request eq "") {
  die("ERROR: parameter 'request' was not set.");
}

if ($request eq "perltest")
{
  # -- send alive ping
  print "Content-type: text/plain\n\n";
  print "TRUE";
  exit();
}

# - id-Parameter pruefen
$id = param('id');
if ($id eq "") {
  die("ERROR: parameter 'id' was not set.");
}

if (-r "uploadrelaisdaten/$id.inf")
{
  if ($request eq "filename")
  {
    # -- send back filename
    open(INF, "<uploadrelaisdaten/$id.inf")
      or die("kann nicht lesen: $!");
    $name = <INF>;
    $name = <INF>;
    close INF;
    print "Content-type: text/plain\n\n";
    print $name;
  }

  if ($request eq "vorhanden")
  {
    # -- Zurueckmelden, dass die ID nicht existiert
    print "Content-type: text/plain\n\n";
    print "TRUE";
  }

  if ($request eq "filedata")
  {
    # -- send back filedata
    open(DATA, "<uploadrelaisdaten/$id.dat")
      or die("Cannot read: $!");
    print "Content-type: application\n\n";
    binmode(DATA);
    while ($zeile = <DATA>) {
      # Daten ausgeben
      print $zeile;

    }
    close DATA;

    # Daten loeschen
    unlink("uploadrelaisdaten/$id.inf");
    unlink("uploadrelaisdaten/$id.dat");
    unlink("uploadrelaisdaten/$id.sta");
  }
}
else
{
  if ($request eq "vorhanden")
  {
    # -- Zurueckmelden, dass die ID nicht existiert
    print "Content-type: text/plain\n\n";
    print "FALSE";
  }
  else
  { die("ERROR: Cannot find upload with ID=$id."); }
}
