#!/usr/bin/env perl
use strict;
use warnings;
use File::Slurp;

my @templates = (
    'resources/views/vcards/templates/bookshop-template.blade.php',
    'resources/views/vcards/templates/coaching-template.blade.php',
);

for my $file (@templates) {
    my $content = read_file($file);

    # Replace: @if(!is_array($X)) { continue; } ?> → @continueIf(!is_array($X))
    $content =~ s/\@if\((!is_array\([^)]+\))\)\s*\{\s*continue;\s*\}\s*\?>/\@continueIf($1)/g;

    # Replace: @if(!is_array($X)) { continue; } @endphp → @continueIf(!is_array($X))
    $content =~ s/\@if\((!is_array\([^)]+\))\)\s*\{\s*continue;\s*\}\s*\@endphp/\@continueIf($1)/g;

    write_file($file, $content);
    print "Fixed: $file\n";
}
