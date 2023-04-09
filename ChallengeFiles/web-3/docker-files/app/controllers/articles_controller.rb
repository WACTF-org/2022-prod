class ArticlesController < ApplicationController
  def index
  end

  def say

    begin
      
      blacklist = ['`', 'Kernel', '%', 'system', 'exec', 'popen']
      if blacklist.any? { |word| params[:say][:text].include?(word) }
        @swanText = "              < Error. Try again. >"
      else
        @swanText = eval "\"               < " + params[:say][:text] + " >\""
      end

    rescue Exception => error
      @swanText = error.message

    end

    render template: "/articles/say"

  end

end
