#!/usr/bin/env ruby

class Robot
	@x
	@y
	@f
	@status
	@FACE
	@firstPlacement
	
	def initialize
		@status = :INACTIVE
		@FACE = Array["NORTH","EAST","SOUTH","WEST"]
		@firstPlacement = true
	end
	
	def place(x,y,fString)		
		if(@FACE.include?(fString))
			@f = @FACE.index(fString).to_i
		else
			puts "Invald Face Direction.\n";	
			return
		end
		
		
		if(@firstPlacement && isOutOfRange(x,y))
			puts "Initial placement cannot out of table\n"
			return
		end
		
		@x = x.to_i
		@y = y.to_i
		
		if(isOutOfRange(@x,@y))
			@status = :INACTIVE
		else
			@status = :ACTIVE
		end	
		
		@firstPlacement = false

	end
	
	def move
		if(@status == :INACTIVE)
			puts "WARNING:Command is ignored.\n"
			return
		end
		
		resultArray = tryToMove(@x,@y,@f)
		
		
		if(resultArray.count == 0)
			puts "WARNING:Robot cannot go further.\n"
		else
			@x = resultArray['x']
			@y = resultArray['y']		
		end
	end
	
	
	def left
		if(@status == :INACTIVE)
			puts "WARNING:Command is ignored.\n"
			return
		end
		
		@f = @f - 1;
		if(@f < 0)
			@f = @f + 4;
		end
	end
	
	def right
		if(@status == :INACTIVE)
			puts "WARNING:Command is ignored.\n"
			return
		end
		
		@f = @f + 1;
		if(@f > 3)
			@f = @f - 4;
		end
	end
	
	def report
		if(@status == :INACTIVE)
			puts "WARNING:Command is ignored.\n"
			return
		end
		puts "Output: #{@x},#{@y},#{@FACE[@f]}\n"
	end
	

	def processInput(line)
		if(line.index('EXIT'))
			exit!
		end
		
		line.strip!
		array = line.split(" ")
		
		if (array.count == 0) 
			return
		end
		
		command = array[0]
		
		case command
			when "PLACE"
				
				if (array.count == 2 )
					args = array[1].split(",")
					if (args.count == 3 )
						#do place
						place(args[0],args[1],args[2])
					end
				else
					puts "Invald Arguments.\n"
				end
			
			when "LEFT"
				left
				#do left
			when "RIGHT"
				right
				#do right
			when "MOVE"
				move
				#do move
			when "REPORT"
				report
				#do report
			when "EXIT"
			  exit!
			else
			  puts "Invalid Command.\n"
		end
		
	end
	
	
	private
	
	def isOutOfRange(x,y)
		x = x.to_i
		y = y.to_i
	
		if(x > 5 || x < 0 || y > 5 || y < 0)
			return true
		else
			return false
		end
	end
	
	def tryToMove(x,y,f)
			
		case f
    		when 0
				y+=1
    		when 1 
				x+=1
    		when 2 
				y-=1
     	   	when 3 
				x-=1 				
		end
	
		if(isOutOfRange(x,y))	
			return {}
		else
			return {'x'=>x,'y'=>y}
		end	
	end
	
end


r = Robot.new
if(ARGV.count > 0 && FileTest::exist?(ARGV[0]))
	
	File.open(ARGV[0], "r") do |infile|
		while (line = infile.gets)

			r.processInput(line)
			
        end
  	end
  	exit!
else

	puts "----------------------\nPlease input following commands\n";
	puts "PLACE 0,0,NORTH\n";
	puts "LEFT\n";
	puts "RIGHT\n";
	puts "MOVE\n";
	puts "REPORT\n";
	puts "EXIT\n----------------------\n";

	while line = gets
		r.processInput(line)
	end
	

end

